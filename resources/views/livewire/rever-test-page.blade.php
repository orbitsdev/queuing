<div>
    rever test

    COUNT: <p>{{ $count }}</p>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Connecting to: incoming-queues");

        window.Echo.channel('incoming-queues')
            .listen('.queue.created', (e) => {
                console.log("✅ Event received!", e);
                // Livewire.dispatch('increaseCount');
               Livewire.dispatch('decreaseCount', {data:1});
                 // no payload needed
            });
    });
    </script>
</div>
