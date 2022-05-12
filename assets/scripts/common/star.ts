window.onload = () => {

    const stars = document.querySelectorAll(".fa-star");
    
    const note = document.querySelector(".note");

    for( let star of stars){
        star.addEventListener("mouseover", function(){
            resetStars();
            this.style.color = "orangered";

            this.classList.add("fa-solid");
            this.classList.remove("fa-regular");

            let previousStar = this.previousElementSibling;

            while(previousStar){
                previousStar.style.color = "orangered";
                previousStar.classList.add( "fa-solid");
                previousStar.classList.remove("fa-regular");
             
                previousStar = previousStar.previousElementSibling;
                
            }
            
        });

        star.addEventListener('click', function(){
            /**@ts-ignore */
            note.value = this.getAttribute('data-value');
            /**@ts-ignore */
            resetStars(note.value);
        })

        star.addEventListener('mouseout', function(){
            /**@ts-ignore */
            resetStars(note.value);
        })
    }

    function resetStars(note = 0){
        
        for( let star of stars){
            let valueStar = star.getAttribute('data-value'); 
             if(parseInt(valueStar) > note){
                /**@ts-ignore */
                
                star.style.color = "grey";
                star.classList.add( "fa-regular");
                star.classList.remove("fa-solid");
            }else{
                console.log(note);
                console.log(star);
                /**@ts-ignore */
                 star.style.color = "orangered";
                 star.classList.add( "fa-solid");
                 star.classList.remove("fa-regular");
            }
         }
    }
}