<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->username }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU ADMIN</li>
            <li data-menu-name="stan">
                <a href="{{ route('admin.stan.index') }}">
                    <i class="fa fa-archive"></i> <span>Stan</span>
                </a>
            </li>
            <li data-menu-name="kategori">
                <a href="{{ route('admin.kategori.index') }}">
                    <i class="fa fa-cubes"></i> <span>Kategori</span>
                </a>
            </li>
            <li data-menu-name="hidangan">
                <a href="{{ route('admin.hidangan.index') }}">
                    <i class="fa fa-coffee"></i> <span>Hidangan</span>
                </a>
            </li>
            <li data-menu-name="meja">
                <a href="{{ route('admin.meja.index') }}">
                    <i class="fa fa-th"></i> <span>Meja</span>
                </a>
            </li>
            <li data-menu-name="transaksi">
                <a href="{{ route('admin.transaksi.index') }}">
                    <i class="fa fa-credit-card"></i> <span>Transaksi</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>