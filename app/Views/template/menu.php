<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url()?>" class="brand-link">
      <img src="<?=base_url('public/adminlte')?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">ALMSIS 1.0</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?=base_url('public/adminlte')?>/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo session('usuario')?></a>
          <i class='text-white'><?php echo session('tipousu')?></i>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="<?php echo base_url()?>" class="nav-link <?php echo isset($act_dashboard) ? 'active' : ''?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview <?php echo isset($li_productos) ? 'menu-open' : ''?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-box-open"></i>
              <p>
                Productos
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php
              if(session('idtipousu') == 1 || session('idtipousu') == 2){
              ?>
              <li class="nav-item">
                <a href="categorias" class="nav-link pl-4 <?php echo isset($act_categorias) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Categorías</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="umedida" class="nav-link pl-4 <?php echo isset($act_umedidas) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Unidad de Medida</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="kardex" class="nav-link pl-4 <?php echo isset($act_kardex) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Kardex</p>
                </a>
              </li>
              <?php
              }
              ?>
              <li class="nav-item">
                <a href="productos" class="nav-link pl-4 <?php echo isset($act_productos) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Producto</p>
                </a>
              </li>              
              <li class="nav-item">
                <a href="requerimiento" class="nav-link pl-4 <?php echo isset($act_requerimiento) ? 'active' : ''?>">
                  <i class="fas fa-check-circle nav-icon"></i>
                    <p>Requerimiento</p>
                </a>
              </li>
            </ul>
          </li>

          <?php
          if(session('idtipousu') == 1 || session('idtipousu') == 2){
          ?>
          <li class="nav-item has-treeview <?php echo isset($li_entradas) ? 'menu-open' : ''?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sign-in-alt"></i>
              <p>
                Entradas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="entradas" class="nav-link pl-4 <?php echo isset($act_entradas) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Entrada</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link pl-4">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Otro</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview <?php echo isset($li_salidas) ? 'menu-open' : ''?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Salidas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="salidas" class="nav-link pl-4 <?php echo isset($act_salidas) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Salida</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="areas" class="nav-link pl-4 <?php echo isset($act_areas) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Areas</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
          }
          ?>

          <?php
          if(session('idtipousu') == 1){
          ?>
          <li class="nav-item has-treeview <?php echo isset($li_config) ? 'menu-open' : ''?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Config
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="usuarios" class="nav-link pl-4 <?php echo isset($act_usuarios) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="backup" class="nav-link pl-4 <?php echo isset($act_backup) ? 'active' : ''?>">
                <i class="fas fa-check-circle nav-icon"></i>
                  <p>Backup BD</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
          }
          ?>

          <!-- li class="nav-header">MULTI LEVEL EXAMPLE</li> -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>