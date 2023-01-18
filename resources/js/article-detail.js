document.addEventListener("DOMContentLoaded", bootstrap);

function bootstrap() {
    console.debug("Welcome to CMSTWT: CMS To Waste Time");
    bootstrapLiking();
}

function bootstrapLiking() {
    
    buttons = {
     "dislike": document.getElementById("button-dislike"),
     "like": document.getElementById("button-like"),
    }

    if(!buttons['dislike'] || !buttons['like']) return;

    let voted = false;
    const article = JSON.parse(document.getElementById("article-detail").dataset.article);

    Object.keys(buttons).forEach((key) => {
        buttons[key].addEventListener("click", function () {
            if (voted) return;
            voted = true;
            likeDislike(article, buttons, key === "like");
        })
    });
}

function getLikesText(likes) {
    if(likes === 1 || likes === -1) return likes + " like";
    return likes + " likes";
}

function likeDislike(article, buttons, like) {
    console.debug("Voting " + like);

    const path = like ?  "article/like/" + article.id : 
    "article/dislike/" + article.id;

    fetch(path, {
        method: "POST",
    })
        .then(function () {
            
            if(like) {
                article.likes++;
            } else {
                article.likes--;
            }

            document.getElementById("like-score").innerText = getLikesText(article.likes);
            Object.values(buttons).forEach((button) => {
                button.setAttribute('disabled', '');
            });

        }).catch(function (error) {
            console.debug(error);
        });
}