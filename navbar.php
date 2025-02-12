<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
            <a href="#">Activities ▼</a>
            <ul class="dropdown-menu">
                <li><a href="events.php">Events</a></li>
                <li><a href="workshops.php">Workshops</a></li>
            </ul>
        </li>
        <li><a href="departments.php">Departments</a></li>
        <li><a href="cesa.php">CESA</a></li>
    </ul>
</nav>

<style>
/* Dropdown Menu Styling */
nav ul { list-style: none; padding: 0; }
nav ul li { display: inline-block; position: relative; }
nav ul li a { text-decoration: none; padding: 10px; display: block; }
nav ul .dropdown-menu { display: none; position: absolute; background: #f9f9f9; padding: 10px; }
nav ul .dropdown:hover .dropdown-menu { display: block; }
</style>
