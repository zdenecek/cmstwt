document.addEventListener("DOMContentLoaded", bootstrap);

let perPage = 10;
let currentPage = 1;
let articles = [];
let counter = null;
let prev = null;
let next = null;

function bootstrap() {
    console.debug("Welcome to CMSTWT: CMS To Waste Time");
    bootstrapCreate();
    bootstrapPagination();
    bootstrapOrdering();
}

function bootstrapPagination() {
    const ids = JSON.parse(document.getElementById("data").dataset.ids);

    if(!ids) {
        console.debug("Fatal: No articles found");
        return;
    }
    articles = ids.map((a) => ({ id: parseInt(a.id), name: a.name, likes: parseInt(a.likes), element:  document.getElementById("article-" + a.id)}));

    counter = document.getElementById("paginator-counter");
    updatePagination();


    prev = document.getElementById("paginator-prev");
    next = document.getElementById("paginator-next");

    prev?.addEventListener("click", function () {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });

    next?.addEventListener("click", function () {
        if (currentPage < getPageCount()) {
            currentPage++;
            console.debug(currentPage);
            updatePagination();
        }
    });
}

function bootstrapCreate() {

    const create = document.getElementById("create-button");

    const modal = document.getElementById("overlay");
    const cancel = document.getElementById("close-button");

    create?.addEventListener("click", function () {
        modal.classList.add("show");
    });

    cancel?.addEventListener("click", function () {
        modal.classList.remove("show");
    });

    document.getElementById("name-input")?.addEventListener("input", function () {
        document.getElementById("submit-button").disabled =
            document.getElementById("name-input")?.value.length === 0;
    });

}

function getPageCount() {
    return Math.ceil(articles.length / perPage);
}

function updatePagination() {

    const count = getPageCount();


    if(currentPage > count) {
        currentPage = count;
    }

    if(currentPage == 1) {
        prev?.classList.add("hidden");
    } else {
        prev?.classList.remove("hidden");
    }

    if(currentPage == count) {
        next?.classList.add("hidden");
    } else {
        next?.classList.remove("hidden");
    }
    
    if(count == 0) {currentPage =0; return;}

    for(let i = 0; i < articles.length; i++) {
        articles[i].element.classList.add("hidden");
    }

    for(let i = (currentPage - 1) * perPage; i < currentPage * perPage; i++) {
        if (articles[i]?.element) {
            articles[i].element.classList.remove("hidden");
            articles[i].element.style.order = i;
        }
    }

    counter.innerText = count;
}



function deleteArticle(id) {
    if (confirm("Are you sure you want to delete the article?")) {
        fetch("article/" + id, {
            method: "DELETE",
        })
            .then(function () {
                article = articles.filter((article) => article.id === id)[0];

                article.element.remove();
                articles = articles.filter((article) => article.id !== id);

                updatePagination();
            }).catch(function (error) {
                console.debug(error);
            });
    }
}



function bootstrapOrdering() {
    const buttons = {
        'id': document.getElementById("order-by-date"),
        'likes': document.getElementById("order-by-likes"),
    } 

    let orderBy = '';
    
    function updateOrdering(field) {

        if(!buttons[field]) return;
        if(orderBy == field) return;
        
        buttons[orderBy]?.removeAttribute('disabled');
        orderBy = field;
        buttons[orderBy].setAttribute('disabled', '');

        articles.sort((a, b) => {
            if(orderBy == 'id') {
                return a.id - b.id;
            } else {
                return b.likes - a.likes;
            }
        });
        currentPage = 1;
        updatePagination();
    }

    updateOrdering('id');

    buttons['likes'].addEventListener("click", e => updateOrdering('likes'));
    buttons['id'].addEventListener("click", e => updateOrdering('id'));

}


