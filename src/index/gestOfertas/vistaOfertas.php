<div class="ofertas-list">
  <?php
  for ($i = 0; $i < count($_SESSION['numOfertas']);$i++) {
      $datoEmp = select("empresas", "foto", "id_EMP=" . $oferta['id_EMP'], "1");
      echo "<div class='card1'>
                <div class='img-card'>
                <img src='" . $datoEmp['foto'] . "' alt=''>
                </div>
            </div>";
  }
  ?>
</div>