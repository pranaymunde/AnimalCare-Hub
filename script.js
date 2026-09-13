document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('input[name="age"]').forEach(function (input) {
    input.addEventListener('input', function () {
      if (this.value !== '' && Number(this.value) < 0) this.value = 0;
      if (this.value !== '' && Number(this.value) > 100) this.value = 100;
    });
  });

  document.querySelectorAll('input[name="phone"]').forEach(function (input) {
    input.addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9+\-\s]/g, '');
    });
  });

  const dashboard = document.getElementById('live-dashboard');
  const metricMap = {
    'Animals': 'animals',
    'Veterinarians': 'veterinarians',
    'Treatments': 'treatments',
    'Vaccinations': 'vaccinations',
    'Adoptions': 'adoptions'
  };

  if (dashboard) {
    fetch('api/live.php', { cache: 'no-store' })
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

        dashboard.querySelectorAll('[data-live-count]').forEach(function (node) {
          const key = node.getAttribute('data-live-count');
          node.textContent = typeof values[key] === 'number' ? values[key] : 0;
        });

        const pendingNode = document.getElementById('pending-adoptions-count');
        if (pendingNode) {
          pendingNode.textContent = typeof data.pending_adoptions === 'number' ? data.pending_adoptions : 0;
        }

        const syncNode = document.getElementById('live-last-sync');
        if (syncNode) {
          const now = new Date();
          syncNode.textContent = 'Updated ' + now.toLocaleTimeString();
        }
      })
      .catch(function () {
        dashboard.querySelectorAll('[data-live-count]').forEach(function (node) {
          node.textContent = node.textContent && node.textContent.trim() !== '0' ? node.textContent : '0';
        });
        const pendingNode = document.getElementById('pending-adoptions-count');
        if (pendingNode) {
          pendingNode.textContent = '0';
        }
      });
  }

  // Flash messages are rendered inside the page by PHP; no browser popups.
  document.querySelectorAll('.flash').forEach(function (box) {
    window.setTimeout(function () {
      box.classList.add('flash-hide');
    }, 5000);
  });
});
