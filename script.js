document.addEventListener('DOMContentLoaded', function () {

    // Age validation
    document.querySelectorAll('input[name="age"]').forEach(function (input) {
        input.addEventListener('input', function () {
            if (this.value !== '' && Number(this.value) < 0) {
                this.value = 0;
            }

            if (this.value !== '' && Number(this.value) > 100) {
                this.value = 100;
            }
        });
    });

    // Phone validation
    document.querySelectorAll('input[name="phone"]').forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9+\-\s]/g, '');
        });
    });

    // Live dashboard
    const dashboard = document.getElementById('live-dashboard');

    if (dashboard) {

        fetch('live.php', {
            cache: 'no-store'
        })

        .then(function (response) {
            if (!response.ok) {
                throw new Error('Live API unavailable');
            }

            return response.json();
        })

        .then(function (data) {

            const values = {};

            if (Array.isArray(data.modules)) {

                data.modules.forEach(function (module) {
                    values[module.name] = Number(module.count) || 0;
                });

            }

            // Update dashboard numbers
            dashboard.querySelectorAll('[data-live-count]').forEach(function (node) {

                const key = node.getAttribute('data-live-count');

                node.textContent =
                    typeof values[key] === 'number'
                        ? values[key]
                        : 0;

            });

            // Pending adoptions
            const pendingNode =
                document.getElementById('pending-adoptions-count');

            if (pendingNode) {
                pendingNode.textContent =
                    typeof data.pending_adoptions === 'number'
                        ? data.pending_adoptions
                        : 0;
            }

            // Last sync time
            const syncNode =
                document.getElementById('live-last-sync');

            if (syncNode) {

                const now = new Date();

                syncNode.textContent =
                    'Updated ' + now.toLocaleTimeString();

            }

        })

        .catch(function (error) {

            console.error('Live dashboard error:', error);

        });

    }

    // Flash messages
    document.querySelectorAll('.flash').forEach(function (box) {

        window.setTimeout(function () {
            box.classList.add('flash-hide');
        }, 5000);

    });

});