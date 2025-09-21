<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

// Fetch accommodations with room data
$sql = "SELECT a.AccommodationID, a.Name, a.Address, a.ContactNum, a.Amenities,
           r.RmType, r.PricePerRmType, r.AvailableRms
    FROM accommodation a
    LEFT JOIN rooms r ON a.AccommodationID = r.AccommodationID
    ORDER BY a.AccommodationID";
$result = $mysqli->query($sql);

// Group results by accommodation
$accommodations = [];
while ($row = $result->fetch_assoc()) {
    $id = $row['AccommodationID'];
    if (!isset($accommodations[$id])) {
        $accommodations[$id] = [
            'Name' => $row['Name'],
            'Address' => $row['Address'],
            'ContactNum' => $row['ContactNum'],
            'Amenities' => $row['Amenities'],
            'Rooms' => []
        ];
    }
    if ($row['RmType']) {
        $accommodations[$id]['Rooms'][] = [
            'RmType' => $row['RmType'],
            'Price' => $row['PricePerRmType'],
            'Available' => $row['AvailableRms']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Accommodation - Home</title>
  <link rel="stylesheet" href="style.css"/>
  <style>
    .cta-button {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 12px;
      background: #0073e6;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    .cta-button:hover {
      background: #005bb5;
    }
    .modal{
      position:fixed;
      top:50%;
      left:50%;
      transform : translate(-50%, -50%) scale(0);
      transition: 200ms ease-in-out;
      border:1px solid black;
      border-radius: 10px;
      z-index: 10;
      background-color: white;
      width: 500px;
      max-width: 80%;
    }
    .modal.active{
      transform : translate(-50%, -50%) scale(1);
    }
    .modal-header{
      padding:10px 15px;
      display:flex;
      justify-content: space-between;
      align-items: center;
      border-bottom:1px solid black;
    }
    .modal-header .title{
      font-size: 1.25rem;
      font-weight: bold;
    }
    .modal-header .close-button{
      cursor: pointer;
      border:none;
      outline:none;
      background:none;
      font-size:1.25rem;
      font-weight:bold;
    }
    .modal-body{
      padding:10px 15px;
    }
    #overlay{
      position:fixed;
      opacity: 0;
      transition: 200ms ease-in-out;
      top:0;
      left:0;
      right:0;
      bottom:0;
      background-color: rgba(0,0,0,0.5);
      pointer-events: none;
    }
    #overlay.active{
      opacity: 1;
      pointer-events:all;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">CPUT STAYS</div>
    <nav>
      <a href="profile.php">Profile</a>
    </nav>
  </header>

  <main>
    <section class="hero-home">
      <h1>Welcome to CPUT STAYS</h1>
      <p>Find and book your ideal student accommodation hassle-free.</p>
    </section>

    <section class="featured">
      <h2>Available Accommodations</h2>
      <div class="cards">
        <?php if (!empty($accommodations)): ?>
          <?php foreach ($accommodations as $id => $accom): ?>
            <?php $modalId = "modal-" . $id; ?>
            <div class="card">
              <img src="room1.jpg" alt="<?= htmlspecialchars($accom['Name']) ?>" />
              <h3><?= htmlspecialchars($accom['Name']) ?></h3>
              <p><strong>Address:</strong> <?= htmlspecialchars($accom['Address']) ?></p>
              <p><strong>Contact:</strong> <?= htmlspecialchars($accom['ContactNum']) ?></p>
              <p><strong>Amenities:</strong> <?= htmlspecialchars($accom['Amenities']) ?></p>

              <?php if (!empty($accom['Rooms'])): ?>
                <p><strong>Rooms:</strong></p>
                <ul>
                  <?php foreach ($accom['Rooms'] as $room): ?>
                    <div>
                      <?= htmlspecialchars($room['RmType']) ?>:
                      R<?= number_format($room['Price'], 2) ?> 
                      (<?= $room['Available'] ?> available)
                  </div>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>

              
              <button data-modal-target="#<?= $modalId ?>">View More Details</button>
            </div>

            <!-- Modal -->
            <div class="modal" id="<?= $modalId ?>">
              <div class="modal-header">
                <div class="title"><?= htmlspecialchars($accom['Name']) ?></div>
                <button data-close-button class="close-button">&times;</button>
              </div>
              <div class="modal-body">
                <p><strong>Address:</strong> <?= htmlspecialchars($accom['Address']) ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($accom['ContactNum']) ?></p>
                <p><strong>Amenities:</strong> <?= htmlspecialchars($accom['Amenities']) ?></p>

                <?php if (!empty($accom['Rooms'])): ?>
                  <h4>Room Types</h4>
                  <ul>
                    <?php foreach ($accom['Rooms'] as $room): ?>
                      <li>
                        <?= htmlspecialchars($room['RmType']) ?>:
                        R<?= number_format($room['Price'], 2) ?> 
                        (<?= $room['Available'] ?> available)
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
                <a href="booking.php?accommodation_id=<?= $id ?>" class="cta-button">Book Now</a>
              </div>
            </div>
          <?php endforeach; ?>
          <div id="overlay"></div>
        <?php else: ?>
          <p>No accommodations available yet.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer>
    <p>© 2025 CPUT STAYS. All rights reserved.</p>
  </footer>

  <script>
    const openModalButtons = document.querySelectorAll('[data-modal-target]')
    const closeModalButtons = document.querySelectorAll('[data-close-button]')
    const overlay = document.getElementById('overlay')

    openModalButtons.forEach(button => {
      button.addEventListener('click', () => {
        const modal = document.querySelector(button.dataset.modalTarget)
        openModal(modal)
      })
    })
      
    if (overlay) {
      overlay.addEventListener('click', ()=>{
        const modals = document.querySelectorAll('.modal.active')
        modals.forEach(modal => {
          closeModal(modal)
        })
      })
    }

    closeModalButtons.forEach(button => {
      button.addEventListener('click', () => {
        const modal = button.closest('.modal')
        closeModal(modal)
      })
    })

    function openModal(modal){
      if (modal==null) return
      modal.classList.add('active')
      overlay.classList.add('active')
    }

    function closeModal(modal){
      if (modal==null) return
      modal.classList.remove('active')
      overlay.classList.remove('active')
    }
  </script>
</body>
</html>
