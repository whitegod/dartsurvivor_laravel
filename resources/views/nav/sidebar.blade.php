<style type="text/css">
  .count_pending{
    border-radius: 50%;
    border:2px solid red;
    width: 20px;
    height: 20px;
    text-align: center; 
    color: white;
    font-weight: 600;
    margin-right: 25px;
    font-size: 11px;
    background-color: red;
  }
  .active_child a{
    color: white !important;
    
  }
</style>
    <aside class="main-sidebar">
        <section class="sidebar">
        <ul class="sidebar-menu">
        @if (Auth::user()->hasRole("Admin"))
            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/dashboard')) ? 'active_child' : ''}}">
                <a href="/admin/dashboard">
                    <i class="fa fa-home" ></i> <span> Dashboard</span>
                </a>
            </li>
            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/detailed-search')) ? 'active_child' : ''}}">
                <a href="/admin/detailed-search">
                    <i class="fa fa-search" ></i> <span> Detailed Search</span>
                </a>
            </li>
            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/reporting')) ? 'active_child' : ''}}">
                <a href="/admin/reporting">
                    <i class="fa fa-book" ></i> <span> Reporting</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <span><strong>Survivor Management</strong></span>
                </a>
            </li>

            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/survivors')) ? 'active_child' : ''}}">
                <a href="/admin/survivors">
                    <i class="fa fa-user" ></i> <span> Survivors</span>
                </a>
            </li>
            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/ttus')) ? 'active_child' : ''}}">
                <a href="/admin/ttus">
                    <i class="fa fa-car" ></i> <span> TTUs</span>
                </a>
            </li>
            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/dashboard')) ? 'active_child' : ''}}">
                <a href="/admin/dashboard">
                    <i class="fa fa-map" ></i> <span> Locations</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <span><strong>Organnization Data</strong></span>
                </a>
            </li>

            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/user_permissions')) ? 'active_child' : ''}}">
                <a href="/admin/user_permissions">
                    <i class="fa fa-group" ></i> <span> Users & Permissions</span>
                </a>
            </li>
            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/dashboard')) ? 'active_child' : ''}}">
                <a href="/admin/dashboard">
                    <i class="fa fa-briefcase" ></i> <span> Case Workers</span>
                </a>
            </li>
            <li class="treeview {{ \Illuminate\Support\Str::startsWith( Request::url(), URL::to('/admin/dashboard')) ? 'active_child' : ''}}">
                <a href="/admin/dashboard">
                    <i class="fa fa-globe" ></i> <span> Mission Information</span>
                </a>
            </li>

        @endif    
        </ul>
        </section>
    </aside>
