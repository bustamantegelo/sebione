<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="#/company" class="nav-link">
                            <i class="fas fa-building"></i>
                            <p>
                                Companies
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#/employee" class="nav-link">
                            <i class="fas fa-users"></i>
                            <p>
                                Employees
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
    export default {
        name: 'AdminLteSidebarComponent'
    }
</script>

<style scoped>

</style>
