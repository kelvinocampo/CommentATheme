<header class="bg-blue-700 text-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold hover:text-blue-200">💬 Comenta un Tema</a>

        <!-- Botón hamburguesa (solo visible en móvil) -->
        <button id="menu-button" class="lg:hidden focus:outline-none">
            <svg class="w-6 h-6 fill-current text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Menú navegación -->
        <nav id="menu" class="hidden flex flex-col items-center lg:flex-row absolute lg:static top-16 left-0 w-full lg:w-auto bg-blue-700 lg:bg-transparent z-10 px-4 py-4 lg:p-0 gap-4 text-sm">
            <a href="index.php" class="hover:underline hover:text-blue-200 transition">Inicio</a>
            <a href="temas.php" class="hover:underline hover:text-blue-200 transition">Temas</a>

            <?php if (isset($_SESSION['nombre']) && $_SESSION['rol'] === 'administrador'): ?>
                <a href="crear_tema.php" class="hover:underline hover:text-blue-200 transition">Crear Tema</a>
            <?php endif; ?>

            <?php if (isset($_SESSION['nombre'])): ?>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition text-center">Cerrar sesión</a>
            <?php else: ?>
                <a href="login.php" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition text-center">Iniciar sesión</a>
            <?php endif; ?>
        </nav>
    </div>

    <script>
        const button = document.getElementById('menu-button');
        const menu = document.getElementById('menu');

        button.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</header>