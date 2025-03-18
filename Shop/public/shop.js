let catalog = []

async function init() {
    try {
        const response = await fetch('../public/index.php?action=api-get');
        if (!response.ok) {
            throw new Error('Erreur lors du chargement du fichier JSON');
        }
        catalog = await response.json();

        let form = document.querySelector('form');
        let errorDiv = document.createElement('div');
        errorDiv.style.color = 'red';
        form.appendChild(errorDiv);
        form.addEventListener('submit', async (event) => {
            const response = await fetch('../public/index.php?action=api-get');
            if (!response.ok) {
                throw new Error('Erreur lors du chargement du fichier JSON');
            }
            catalog = await response.json();
        });
        displayItem(catalog);
        updateFilter();
        totalPrice();
    } catch (error) {
        console.error('Erreur lors de l\'initialisation :', error);
    }

    let imgInput = document.getElementById("image");
    let imgElement = document.getElementById("img-src");
    imgInput.addEventListener('change', (event) => {
        let file = event.target.files[0];
        if (file) {
            imgElement.src = URL.createObjectURL(file);
        }
        });
}


function updateFilter() {
    let filter = document.getElementById("cat-filter");
    filter.innerHTML = '<option value="all">Toutes les catégories</option>';
    let list = new Set(catalog.map(produit => produit.category));
    list.forEach((cat) => {
        let option = document.createElement("option");
        option.value = cat;
        option.textContent = cat;
        filter.appendChild(option);
    });
}


function filterProducts() {
    let s_category = document.getElementById("cat-filter").value;
    let f_products = s_category == "all" ? catalog : catalog.filter((item) => item.category === s_category);
    displayItem(f_products);
}

function searchProducts() {
    let s_category = document.getElementById("cat-filter").value;
    let f_products = s_category === "all" ? catalog : catalog.filter((item) => item.category === s_category);
    let entry = document.getElementById("search-products").value.toLowerCase();
    let ff_products = f_products.filter(p => p.name.toLowerCase().includes(entry));
    displayItem(ff_products);
}

function totalPrice() {
    let total = catalog.reduce((accumulator, currentValue) => accumulator + parseFloat(currentValue.price), 0);
    let totalHTML = document.createElement("h3");
    totalHTML.innerHTML = "Prix total du catalogue : " + total.toFixed(1) + " €";
    let container = document.getElementById("total");
    let oldTotal = container.querySelector("h3");
    if (oldTotal) {
        container.removeChild(oldTotal);
    }
    container.appendChild(totalHTML);
}

function applyDiscount() {
    let s_category = document.getElementById("cat-filter").value;
    let f_products = s_category === "all" ? catalog : catalog.filter((item) => item.category === s_category);
    let n_products = f_products.map(p => ({...p, price: p.price - (p.price * 0.1)}));
    displayItem(n_products);

}

function resetDisplay() {
    let s_category = document.getElementById("cat-filter").value;
    let f_products = s_category === "all" ? catalog : catalog.filter((item) => item.category == s_category);
    displayItem(f_products)
}

function displayItem(iterable) {

    let productGrid = document.getElementById('product-grid');
    productGrid.innerHTML = '';
    if (!iterable || !Array.isArray(iterable)) {
        productGrid.innerHTML = '<p>Aucun produit à afficher</p>';
        return;
    }
    iterable.forEach((product) => {
        console.log("product.price:", product.price, "Type:", typeof product.price);
        let productCard = document.createElement('div');
        productCard.classList.add('product-card');

        let productImage = document.createElement('img');
        productImage.src = product.image;
        productImage.alt = product.name;
        productImage.src = product.image || '../images/default.jpg';
        productImage.alt = product.name || 'Produit sans nom';

        let productName = document.createElement('h3');
        productName.textContent = product.name;
        productName.textContent = product.name || 'Nom non disponible';

        let productPrice = document.createElement('p');
        productPrice.classList.add('price');
        productPrice.textContent = `Prix : ${Number(product.price).toFixed(1)} €`;
        productPrice.textContent = `Prix : ${Number(product.price || 0).toFixed(1)} €`;

        let productStock = document.createElement('p');
        productStock.classList.add('stock');
        productStock.textContent = product.stock ? 'En stock' : 'Rupture de stock';

        // Ajouter les liens CRUD
        let actionsDiv = document.createElement('div');
        actionsDiv.classList.add('actions');
        console.log(product);
        let editLink = document.createElement('a');
        editLink.href = `?action=update&id=${product.id_product}`;
        editLink.textContent = 'Modifier';
        editLink.classList.add('edit-link');

        let deleteLink = document.createElement('a');
        deleteLink.href = `?action=delete&id=${product.id_product}`;
        deleteLink.textContent = 'Supprimer';
        deleteLink.classList.add('delete-link');
        deleteLink.onclick = (e) => {
            if (!confirm('Voulez-vous vraiment supprimer ce produit ?')) {
                e.preventDefault();
            }
        };

        actionsDiv.appendChild(editLink);
        actionsDiv.appendChild(deleteLink);

        productCard.appendChild(productImage);
        productCard.appendChild(productName);
        productCard.appendChild(productPrice);
        productCard.appendChild(productStock);
        productCard.appendChild(actionsDiv);
        productGrid.appendChild(productCard);
    });
}
