<?php include '../../../loader/loader.php' ?>

<div class="sidebar close">
  <ul class="nav-links">
    <li>
      <a href="#">
        <i class='bx bx-pie-chart-alt-2'></i>
        <span class="link_name">Tableau de bord</span>
      </a>
      <ul class="sub-menu blank">
        <li><a href="../dashboard.php">Tableau de bord</a></li>
      </ul>
    </li>
    <li class="active">
      <div class="iocn-link">
        <a href="#">
          <i class='bx bx-book-alt'></i>
          <span class="link_name">Gérer les livres</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a href="add-book.php">Ajouter un livre</a></li>
        <li><a href="view-book.php">Voir les livres</a></li>
      </ul>
    </li>
    <li>
        <div class="iocn-link">
          <a href="category.php">
          <i class='bx bx-category'></i>
            <span class="link_name">Catégorie</span>
          </a>
        </div>
        <ul class="sub-menu blank">
          <li><a href="category.php">Catégorie</a></li>
        </ul>
      </li>
    <li>
      <div class="iocn-link">
        <a href="issue-book.php">
          <i class='bx bx-folder-open'></i>
          <span class="link_name">Émettre des livres</span>
        </a>
      </div>
      <ul class="sub-menu blank">
        <li><a href="issue-book.php">Émettre des livres</a></li>
      </ul>
    </li>
    <li>
      <a href="view-issue-book.php">
        <i class='bx bxs-grid'></i>
        <span class="link_name">Voir tous les livres empruntés</span>
      </a>
      <ul class="sub-menu blank">
        <li><a href="view-issue-book.php">Voir tous les livres empruntés</a></li>
      </ul>
    </li>
    <li>
      <a href="view-return-book.php">
        <i class='bx bx-time'></i>
        <span class="link_name">Voir tous les livres retournés</span>
      </a>
      <ul class="sub-menu blank">
        <li><a href="view-return-book.php">Voir tous les livres retournés</a></li>
      </ul>
    </li>
    <li>
      <a href="view-book-request.php">
        <i class='bx bx-list-ul'></i>
        <span class="link_name">Voir les demandes de livres</span>
      </a>
      <ul class="sub-menu blank">
        <li><a href="view-book-request.php">Voir les demandes de livres</a></li>
      </ul>
    </li>
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class='bx bxs-group'></i>
          <span class="link_name">Gérer les utilisateurs</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a href="add-user.php">Ajouter des utilisateurs</a></li>
        <li><a href="view-user.php">Voir tous les utilisateurs</a></li>
      </ul>
    </li>
  </ul>
</div>