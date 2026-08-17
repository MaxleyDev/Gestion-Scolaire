</div> <!-- .container -->
<script>
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'k') {
        e.preventDefault();
        document.querySelector('input[name="q"]').focus();
    }
});
</script>
</body>
</html>