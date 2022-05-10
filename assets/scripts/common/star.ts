window.onload = () => {

    const stars = document.querySelectorAll(".fa-star")

    const note = document.querySelector(".note")

    for( let star of stars){
        star.addEventListener("mouseover", function(){
            resetStars();
            this.style.color = "red";

            let previousStar = this.previousElementSibling;

            while(previousStar){
                previousStar.style.color = "red";
                previousStar = previousStar.previousElementSibling;
            }
            
        });

        star.addEventListener('click', function(){
            /**@ts-ignore */
            note.value = this.dataset.value;
        })

        star.addEventListener('mouseout', function(){
            /**@ts-ignore */
            resetStars(note.value);
        })
    }

    function resetStars(note = 0){
        
        for( let star of stars){
            /**@ts-ignore */
             if(star.dataset.value>note){
                /**@ts-ignore */
                star.style.color = "grey";
            }else{
                /**@ts-ignore */
                 star.style.color = "red";
            }
         }
    }
}