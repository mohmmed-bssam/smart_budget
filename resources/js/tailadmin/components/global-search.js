export const initGlobalSearch = () => {
    const searchInput = document.getElementById('globalSearch');

    if (!searchInput) return;

    const usersPage = document.querySelector('[data-search-page="users"]');

    if (usersPage) {
        const rows = usersPage.querySelectorAll('.user-row');
        const emptyRow = document.getElementById('usersSearchEmpty');

        searchInput.addEventListener('input', function () {
            const searchValue = this.value
                .toLowerCase()
                .trim();

            let visibleRows = 0;

            rows.forEach((row) => {
                const searchableText =
                    row.dataset.search?.toLowerCase() || '';

                const matches = searchableText.includes(searchValue);

                row.classList.toggle('hidden', !matches);

                if (matches) {
                    visibleRows++;
                }
            });

            if (emptyRow) {
                emptyRow.classList.toggle(
                    'hidden',
                    visibleRows !== 0 || searchValue === ''
                );
            }
        });
    }
};

export default initGlobalSearch;
