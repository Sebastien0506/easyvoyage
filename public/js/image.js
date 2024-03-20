document.addEventListener('DOMContentLoaded', function(){
    let links = document.querySelectorAll("[data-delete]")
   
    
   for (const link of links) {
    //On ecoute le clic
    link.addEventListener("click", function(e){
        //On empeche la navigation
        e.preventDefault()

        //On demande confirmation
        if(confirm("Voulez-vous supprimer cette image ?")){
            // On envoie une requete Ajax ver le href du lien avec la méthode DELETE
            fetch(this.getAttribute("href"), {
                method: "DELETE",
                headers: {
                    'X-Requested-With': "XMLHttRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({"_token": this.dataset.token})
            }).then(
                //On recupère la reponse en json
                response => response.json()
            ).then(data => {
                if(data.success)
                    this.parentElement.remove()
                else
                   alert(data.error)
            }).catch(e => alert(e))
        }
    })
   }
})