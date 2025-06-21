<div>
    rever test


    <script>
  document.addEventListener('DOMContentLoaded', function() {
    console.log("Connecting to: incoming-queues");

    window.Echo.channel('incoming-queues')
        .listen('.queue.created', (e) => {
            console.log("✅ Event received!", e);
        });
});
    </script>
</div>

