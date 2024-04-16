window.onload=() => {
    const searchInput = document.querySelector("#search-user");
    searchInput.addEventListener('input',() =>{
        searchVal=searchInput.value ;
        const Params = new URLSearchParams();
        Params.append('key',searchVal);
        console.log(Params.toString())

        const Url = new URL(window.location.href);
        fetch(Url.pathname  + "?" + Params.toString(),{
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(response =>response.json()
        ).then(data =>{
            const content=document.querySelector("#usercontent");
            content.innerHTML=data.content ;
        }).catch( e=> alert(e));
    });
}