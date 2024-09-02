<div class="navbar bg-base-100">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <li><a>Item 1</a></li>
                <li>
                    <a>Parent</a>
                    <ul class="p-2">
                        <li><a>Submenu 1</a></li>
                        <li><a>Submenu 2</a></li>
                    </ul>
                </li>
                <li><a>Item 3</a></li>
            </ul>
        </div>
        <a class="btn btn-ghost text-xl">daisyUI</a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li>
                <details>
                    <summary>users</summary>
                    <ul class="p-2">
                        <li><a href="login.php">login</a></li>
                        <li><a href="register.php">register</a></li>
                        <li><a href="index.php">seeUsers</a></li>
                        <li><a href="logout.php">logout</a></li>
                        <li><a href="userUpdate.php">update</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <details>
                    <summary>roles</summary>
                    <ul class="p-2">
                        <li><a href="roleAdd.php">Create</a></li>
                        <li><a href="roleUpdate.php">Roles</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <details>
                    <summary>permissions</summary>
                    <ul class="p-2">
                        <li><a href="permissionAdd.php">Create</a></li>
                        <li><a href="permissionUpdate.php">Update</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <details>
                    <summary>features</summary>
                    <ul class="p-2">
                        <li><a href="addFeature.php">Create</a></li>
                        <li><a href="updateFeature.php">Update</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <a href="assignUserToRole.php">Assign User To Role</a>
            </li>
            <li>
                <a href="assignRolePermission.php">Assign Role to Permission</a>
            </li>

        </ul>
    </div>
    <div class="navbar-end">
        <a class="btn">Button</a>
    </div>
</div>