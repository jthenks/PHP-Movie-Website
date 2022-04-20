document.addEventListener('DOMContentLoaded', () => {

  let castContent = document.querySelector("#castContent"); 
  let crewContent = document.querySelector("#crewContent");
  let castButton = document.querySelector("#castButton");
  let crewButton = document.querySelector("#crewButton");
  let posterModal = document.querySelector("#posterModal");



  function popupLaptopPoster()
  {
    posterModal.style.display = "block";
  }

  function closePopup()
  {
    posterModal.style.display = "none";
  }


  function setDisplayNone(element)
  {
    element.style.display = "none";
  }

  function setDisplayGrid(element)
  {
    element.style.display = "grid";
  }

  function toggleTab(button)
  {
    button.classList.toggle("activeTab");
  }

  castButton.addEventListener('click', () => {
    setDisplayGrid(castContent);
    setDisplayNone(crewContent);
    if (!castButton.classList.contains("activeTab"))
    {
      toggleTab(castButton);
      toggleTab(crewButton);
    }
  
  });

  crewButton.addEventListener('click', () => {
    setDisplayGrid(crewContent);
    setDisplayNone(castContent);
    
    if (!crewButton.classList.contains("activeTab"))
    {
      toggleTab(castButton);
      toggleTab(crewButton);
    }
  });


  document.addEventListener('click', (e) => {
 
    if(e.target && e.target.id == "poster")
    {
      popupLaptopPoster();
    }
    else if(e.target && e.target.classList == "close")
    {
      closePopup();
    } 
  });

let favButton = document.querySelector(".favouriteButton");

if(favButton != null){
  favButton.addEventListener('click', (e) => {

    if(e.target.nodeName == "SPAN") {
                
        e.target.classList.contains('checked') ? uncheckStar(e) : checkStar(e);
    }
})
  
}


function checkStar(e) {
    e.target.classList.add('checked');

 


}

function uncheckStar(e) {

    e.target.classList.remove('checked');

}

});
