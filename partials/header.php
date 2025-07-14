<header class="bg-blue-700 text-white shadow-md relative z-50">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold hover:text-blue-200 flex items-center">💬 Comenta un Tema</a>

        <!-- Botón hamburguesa (visible solo en móviles) -->
        <button id="menu-button" class="lg:hidden focus:outline-none flex items-center justify-center h-10 w-10" aria-label="Abrir menú">
            <svg class="w-6 h-6 fill-current text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Menú navegación -->
        <nav id="menu"
            class="hidden lg:flex flex-col lg:flex-row absolute lg:relative
            top-full left-0 w-full lg:w-auto bg-blue-700 lg:bg-transparent px-4 lg:px-0
            py-4 lg:py-0 gap-4 lg:gap-6 text-sm items-center">
            <div class="flex flex-col lg:flex-row items-center w-full lg:w-auto gap-4 lg:gap-6">
                <a href="index.php" class="hover:underline hover:text-blue-200 transition py-1 lg:py-0 w-full lg:w-auto text-center">Inicio</a>
                <a href="temas.php" class="hover:underline hover:text-blue-200 transition py-1 lg:py-0 w-full lg:w-auto text-center">Temas</a>

                <?php if (isset($_SESSION['nombre']) && $_SESSION['rol'] === 'administrador'): ?>
                    <a href="crear_tema.php" class="hover:underline hover:text-blue-200 transition py-1 lg:py-0 w-full lg:w-auto text-center">Crear Tema</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['nombre'])): ?>
                    <a href="logout.php"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition w-full lg:w-auto text-center">Cerrar sesión</a>
                <?php else: ?>
                    <a href="login.php"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition w-full lg:w-auto text-center">Iniciar sesión</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <script>
        const button = document.getElementById('menu-button');
        const menu = document.getElementById('menu');

        button.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        // Cierra el menú cuando haces clic en cualquier enlace (en móviles)
        document.querySelectorAll('#menu a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    menu.classList.add('hidden');
                }
            });
        });
    </script>
</header>