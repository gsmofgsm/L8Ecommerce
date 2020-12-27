(function() {
    const searchClient = algoliasearch('K9IYITZ09W', '37b8e9885ad006858ac749b0146b9e98');

    const search = instantsearch({
        indexName: 'products',
        searchClient,
        routing: true
    });

    search.addWidgets([
        instantsearch.widgets.searchBox({
            container: '#searchbox',
        }),

        instantsearch.widgets.hits({
            container: '#hits',
            templates: {
                empty: 'No results for <q>{{ query }}</q>',
                item: function(item) {
                    return `
                        <div class="result-title">
                            ${item._highlightResult.name.value}
                        </div>
                        <div class="result-details">
                            ${item._highlightResult.details.value}
                        </div>
                        <div class="result-price">
                            $${(item.price / 100).toFixed(2)}
                        </div>
                        <img src="${window.location.origin}/${item.image}" alt="img" class="algolia-thumb-result">
                    `;
                }

                //     `
                //   <h2>
                //     {{ __hitIndex }}:
                //     {{#helpers.highlight}}{ "attribute": "name" }{{/helpers.highlight}}
                //   </h2>
                //   <p>{{ description }}</p>
                // `,
            },
        }),

        instantsearch.widgets.pagination({
            container: '#pagination',
            totalPages: 20,
        }),

        instantsearch.widgets.hitsPerPage({
            container: '#hits-per-page',
            items: [
                { label: '8 hits per page', value: 8 },
                { label: '16 hits per page', value: 16, default: true },
            ],
        }),

        instantsearch.widgets.stats({
            container: '#stats',
        }),

        instantsearch.widgets.refinementList({
            container: '#refinement-list',
            attribute: 'price',
        }),
    ]);

    search.start();
})();
