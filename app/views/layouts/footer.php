</main> <!-- closes .container -->
<footer>
    <p>&copy; <?php echo date("Y"); ?> Ashesi University - AMS</p>
</footer>
<script>
    // Make APP_BASE_URL available to JavaScript
    const APP_BASE_URL = "<?php echo htmlspecialchars($APP_BASE_URL, ENT_QUOTES, 'UTF-8'); ?>";
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo htmlspecialchars($APP_BASE_URL); ?>public/js/main.js"></script>
</body>
</html>
