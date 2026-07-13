<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register("{{ url('/service-worker.js') }}").catch(function (error) {
      console.warn('Service worker registration failed:', error);
    });
  });
}
</script>
