/**********************************************************************/
                    /* variable*/
/**********************************************************************/

const svgEl = document.querySelectorAll("#imgVect,#imgVect_2, #imgVect_3, #imgVect_4,#imgVect_5, #imgVect_6,#imgVect_7, #imgVect_8, #imgVect_9,#imgVect_10, #imgVect_11, #imgVect_12")
const menu_toggle = document.querySelector('.menu-toggle');
const sidebar = document.querySelector('.sidebar');
const btnDarkMode = document.querySelector("#cont__toggle");
const body = document.querySelector("html");
const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');

/**********************************************************************/
                    /* function*/
/**********************************************************************/


document.addEventListener("DOMContentLoaded", function () {
    
    /**
     * parallax 
    */
    //ecouter le deplacement de la souris 

    document.addEventListener("mousemove", mouveIco);

    function mouveIco(el) {
    // selection de chaque elements
        svgEl.forEach(function (element) {
            const moove = element.getAttribute("data-speed"); 
            const y = (el.pageY * moove) / 200;
            const x = (el.pageX * moove) / 200;
            element.style.transform = "translateX(" + x + "px) translateY(" + y + "px)";
        });
    }

/**
 * Dark mode 
*/
// fucntion de de verifaction de button check
    function switchTheme(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            // definir la valeur de l'attribut data theme
        }
        else {
            document.documentElement.setAttribute('data-theme', 'light');
            
        }
    }
    
    //  ecouter l'evenement change pour verifier la function checkbox 

    toggleSwitch.addEventListener('change', switchTheme, false);
    
    //Utiliser localStorage pour stocker si le mode sombre est actuellement activé ou désactivé.
    function switchTheme(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark'); 
        }
        else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light'); 
        }
    }
    const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);

        if (currentTheme === 'dark') {
            toggleSwitch.checked = true;
        }
    }
    
    // menu burger 
    
        menu_toggle.addEventListener('click', () => {
        menu_toggle.classList.toggle('is-active');
        sidebar.classList.toggle('is-active');
    });


});
