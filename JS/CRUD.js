// getters
var productName = document.getElementById("productName")
var productPrice = document.getElementById("productPrice")
var productCategory = document.getElementById("productCategory")
var productDescription = document.getElementById("productDescription")
var productImage = document.getElementById("productImage")
var productSearch = document.getElementById("productSearch")
var addBtn = document.getElementById("addBtn")
var updBtn = document.getElementById("updBtn")
var productList = []
var updatedIndex;


if (localStorage.getItem("productsArray") != null) {
    productList = JSON.parse(localStorage.getItem("productsArray"))
    displayProducts(productList)
}



function addProduct() {


    var product = {
        name: productName.value,
        price: productPrice.value,
        category: productCategory.value,
        description: productDescription.value,
        image: `../Images/${productImage.files[0].name}`
    }
    productList.push(product)
    localStorage.setItem("productsArray", JSON.stringify(productList))
    clearInputsValue()
    displayProducts(productList)
}


function clearInputsValue() {
    productName.value = ""
    productPrice.value = ""
    productCategory.value = ""
    productDescription.value = ""
}


function displayProducts(array) {
    var cartona = ""
    for (var i = 0; i < array.length; i++) {
        cartona += `<div class="col-md-4">
                        <div class="card position-relative">
                            <img src="${productList[i].image}" class="card-img-top" alt="iphone17">
                            <div class="card-body">
                                <span class="badge text-bg-primary p-2 position-absolute top-0 end-0 m-2 fs-6">${array[i].category}</span>
                                <h3 class="card-title">${array[i].name.split(" ").slice(0, 3).join(" ")}</h3>
                                <p class="card-text fs-6">${array[i].description}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="text-primary">${array[i].price} EGP</h4>
                                    <div class="d-flex">
                                        <button onclick="deleteProduct(${i})" id="deleteProduct" class="btn btn-outline-danger rounded-end-0">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button onclick="getProductToUpdate(${i})" id="updateProduct" class="btn btn-outline-warning rounded-start-0">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `
    }
    document.getElementById("rowData").innerHTML = cartona
}


function deleteProduct(index) {
    console.log(index);
    productList.splice(index, 1)
    displayProducts(productList)
    localStorage.setItem("productsArray", JSON.stringify(productList))
}

function searchProduct() {
    console.log(productSearch.value);
    var searchArray = []
    for (var i = 0; i < productList.length; i++) {
        if (productList[i].name.toLowerCase().includes(productSearch.value.trim().toLowerCase()) == true) {
            searchArray.push(productList[i])
        }
    }

    displayProducts(searchArray)
}


function getProductToUpdate(i) {
    updatedIndex = i
    console.log("hello", i);
    productName.value = productList[i].name
    productPrice.value = productList[i].price
    productCategory.value = productList[i].category
    productDescription.value = productList[i].description
    addBtn.classList.add("d-none", "hambozo")
    updBtn.classList.remove("d-none")

}

function updateProduct() {
    productList[updatedIndex].name = productName.value
    productList[updatedIndex].price = productPrice.value
    productList[updatedIndex].category = productCategory.value
    productList[updatedIndex].description = productDescription.value
    displayProducts(productList)
    localStorage.setItem("productsArray", JSON.stringify(productList))
    clearInputsValue()
    addBtn.classList.remove("d-none")
    updBtn.classList.add("d-none")

}





