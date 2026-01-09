@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const farmSelect = document.getElementById('farm_id');
        const shedSelect = document.getElementById('shed_id');
        const flockSelect = document.getElementById('flock_id');

        if (!farmSelect || !shedSelect || !flockSelect) {
            return;
        }

        const shedApiBase = shedSelect.dataset.apiBase;
        const flockApiBase = flockSelect.dataset.apiBase;
        const initialFarm = farmSelect.value;
        const initialShed = shedSelect.dataset.selected;
        const initialFlock = flockSelect.dataset.selected;

        if (initialFarm) {
            loadSheds(initialFarm, initialShed).then(function() {
                if (initialShed) {
                    loadFlocks(initialShed, initialFlock);
                }
            });
        }

        farmSelect.addEventListener('change', function() {
            loadSheds(this.value, null);
        });

        shedSelect.addEventListener('change', function() {
            loadFlocks(this.value, null);
        });

        function resetSelect(select, placeholder) {
            select.innerHTML = `<option value=\"\">${placeholder}</option>`;
        }

        function loadSheds(farmId, preselect) {
            resetSelect(shedSelect, 'Select Shed');
            resetSelect(flockSelect, 'Select Flock');

            if (!farmId) {
                return Promise.resolve();
            }

            return fetch(`${shedApiBase}/${farmId}`)
                .then(response => response.json())
                .then(sheds => {
                    sheds.forEach(shed => {
                        const option = document.createElement('option');
                        option.value = shed.id;
                        option.textContent = shed.name;
                        shedSelect.appendChild(option);
                    });

                    if (preselect) {
                        shedSelect.value = preselect;
                    }
                })
                .catch(() => {});
        }

        function loadFlocks(shedId, preselect) {
            resetSelect(flockSelect, 'Select Flock');

            if (!shedId) {
                return;
            }

            fetch(`${flockApiBase}/${shedId}`)
                .then(response => response.json())
                .then(flocks => {
                    flocks.forEach(flock => {
                        const option = document.createElement('option');
                        option.value = flock.id;
                        option.textContent = flock.name;
                        flockSelect.appendChild(option);
                    });

                    if (preselect) {
                        flockSelect.value = preselect;
                    }
                })
                .catch(() => {});
        }
    });
</script>
@endpush
