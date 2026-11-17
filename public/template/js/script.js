window.addEventListener("scroll",function(){

    const navbar=document.querySelector(".navbar");

    if(window.scrollY>80){

        navbar.classList.add("scrolled");

    }else{

        navbar.classList.remove("scrolled");

    }

});



// Smooth Scroll

document.querySelectorAll('a[href^="#"]').forEach(anchor=>{

    anchor.addEventListener("click",function(e){

        e.preventDefault();

        document.querySelector(this.getAttribute("href"))

            ?.scrollIntoView({

                behavior:"smooth"

            });

    });

});

const topBtn=document.getElementById("topBtn");

window.addEventListener("scroll",()=>{

if(window.pageYOffset>300){

topBtn.style.display="block";

}else{

topBtn.style.display="none";

}

});

topBtn.onclick=()=>{

window.scrollTo({

top:0,

behavior:"smooth"

});

};