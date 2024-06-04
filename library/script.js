var searchFilter = () => {
    const input = document.querySelector(".form-control");
    const cards = document.getElementsByClassName("col");
    console.log(cards[1])
    let filter = input.value
    for (let i = 0; i < cards.length; i++) {
        let title = cards[i].querySelector(".card-body");
        if (title.innerText.toLowerCase().indexOf(filter.toLowerCase()) > -1) {
            cards[i].classList.remove("d-none")
        } else {
            cards[i].classList.add("d-none")
        }
    }
}