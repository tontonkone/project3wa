/* **********************variable*************** 
*/

const burger = document.getElementById("burger");
const menu = document.querySelector("#menu");

const sign_up_link = document.querySelector("#sign-up-link");
const sign_in_link = document.querySelector("#sign-in-link");
const container = document.querySelector(".container-ipt");
const login = document.querySelector(".container-ipt__login");
const register = document.querySelector(".container-ipt__register");
const elements = document.querySelectorAll(".container__img");


/***************************************************
 *                      Function
 * **************************************************
*/
burger.addEventListener("click", () => 
{ 
    menu.classList.toggle('active')
});

/**
 * form 
 */

    sign_in_link.addEventListener("click", () => {
        login.style.visibility = "hidden";
        register.style.visibility = "visible";
        });
    sign_up_link.addEventListener("click", () => {
        register.style.visibility = "hidden";
        login.style.visibility = "visible";
    });
/****************************************
 *             parallax
 * *************************************
 */

document.addEventListener('mousemove', mouveIco);
    
function mouveIco(el) {
    document.querySelectorAll(
    "#imgVect,#imgVect_2, #imgVect_3, #imgVect_4,#imgVect_5, #imgVect_6,#imgVect_7, #imgVect_8, #imgVect_9,#imgVect_10, #imgVect_11, #imgVect_12"
    ).forEach(function (element) {
        const moove = element.getAttribute("data-speed");
        const y = (el.pageY * moove) / 200;
        const x = (el.pageX * moove) / 200;
        element.style.transform =
        "translateX(" + x + "px) translateY(" + y + "px)";
    });
}

/*     sign_in_link.addEventListener("click", () => {
    console.log("va");
    container.classList.remove("container-ipt__login");
    container.classList.remove("sign-up-mode");
    }); */