const form = document.getElementById("invoice-form");
const addProductButton = document.getElementById("add-product");
const productsTable = document.querySelector(".product-section table");
const totalAmount = document.getElementById("total-amount");
const includeGstRadio = document.querySelector('input[name="gst"]');
const gstRate = 0.18;
let productCount = 1;

// Add event listener for the Add New Product button
// Add event listener for the Add New Product button
addProductButton.addEventListener("click", () => {
  const productRow = `
    <tr class="product-row">
      <td><input type="text" class="product-input" name="product[]" required></td>
      <td><input type="number" class="product-input" name="quantity[]" min="1" required></td>
      <td><input type="number" class="product-input" name="price[]" min="0" step="0.01" required></td>
      <td class="product-total"></td>
      <td><button type="button" class="remove-product">Remove</button></td>
    </tr>
  `;
  productsTable.querySelector("tbody").insertAdjacentHTML("beforeend", productRow);
  productCount++;
});


// Add event listener for the Remove button on each product row
productsTable.addEventListener("click", (event) => {
  if (event.target.classList.contains("remove-product")) {
    event.target.closest("tr").remove();
    productCount--;
  }
});

// Add event listener for the form submission
form.addEventListener("submit", (event) => {
  event.preventDefault();
  const formData = new FormData(event.target);
  const invoice = Object.fromEntries(formData.entries());
  console.log(invoice);
});

// Calculate the  amount when the price or quantity of a product is changed
includeGstRadio.addEventListener("change", () => {
  const includeGst = includeGstRadio.checked;
  const gstAmount = includeGst ? subtotal * gstRate : 0;
  const total = subtotal + gstAmount;
  totalAmount.textContent = "Rs " + total.toFixed(2);

  // update GST input value if applicable
  const gstInput = document.querySelector('input[name="gst-amount"]');
  if (includeGst) {
    gstInput.value = gstAmount.toFixed(2);
  } else {
    gstInput.value = '';
  }
});

productsTable.addEventListener("change", () => {
  let subtotal = 0;
  productsTable.querySelectorAll(".product-row").forEach((row) => {
    const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value);
    const price = parseFloat(row.querySelector('input[name="price[]"]').value);
    const total = quantity * price;
    const productTotal = row.querySelector(".product-total");
    productTotal.textContent = "Rs " + total.toFixed(2);
    subtotal += total;
  });

  // Calculate GST based on radio button selection
  const includeGst = includeGstRadio.checked;
  let gst = 0;
  if (includeGst) {
    gst = subtotal * gstRate;
  }

  // Calculate the total amount
  const total = subtotal + gst;
  totalAmount.textContent = "Rs " + total.toFixed(2);

  // update GST input value if applicable
  const gstInput = document.querySelector('input[name="gst-amount"]');
  if (includeGst) {
    gstInput.value = gst.toFixed(2);
  } else {
    gstInput.value = '';
  }
});



form.addEventListener('submit', (event) => {
  event.preventDefault();

  // get form data
  const formData = new FormData(form);

  // get include GST value
  const includeGst = formData.get('gst') === 'included';

  // get product data
  const products = [];
  const productRows = document.querySelectorAll('.product-row');
  productRows.forEach((row) => {
    const product = row.querySelector('input[name="product[]"]').value;
    const quantity = parseInt(row.querySelector('input[name="quantity[]"]').value);
    const price = parseFloat(row.querySelector('input[name="price[]"]').value);

    if (product && quantity && price) {
      products.push({ product, quantity, price });
    }
  });

  // calculate totals
  const subTotal = products.reduce((total, { quantity, price }) => total + quantity * price, 0);
  const gst = includeGst ? subTotal * 0.18 : 0;
  const total = subTotal + gst;

  // create PDF data
  const pdfData = {
    address: formData.get('address'),
    email: formData.get('email'),
    products,
    subTotal,
    gst,
    total,
    includeGst,
  };

  // set value of hidden input field
  const pdfDataInput = document.getElementById('pdf-data');
  pdfDataInput.value = JSON.stringify(pdfData);

  // submit the form
  form.submit();
});



// Get the "Add New Term" button and the terms list
const addTermBtn = document.getElementById("addTermBtn");
const termsList = document.getElementById("termsList");

// Add an event listener to the "Add New Term" button
addTermBtn.addEventListener("click", () => {
  // Create a new list item and input field
  const newTermLi = document.createElement("li");
  const newTermInput = document.createElement("input");
  newTermInput.type = "text";
  newTermInput.name = "term[]";
  newTermInput.value = "";

  // Create a new "Remove" button
  const newRemoveBtn = document.createElement("button");
  newRemoveBtn.type = "button";
  newRemoveBtn.className = "removeTermBtn";
  newRemoveBtn.textContent = "Remove";

  // Add the new input field and "Remove" button to the list item
  newTermLi.appendChild(newTermInput);
  newTermLi.appendChild(newRemoveBtn);

  // Add the new list item to the terms list
  termsList.appendChild(newTermLi);
});

// Add an event listener to the terms list to handle clicks on the "Remove" button
termsList.addEventListener("click", (event) => {
  if (event.target.classList.contains("removeTermBtn")) {
    // Remove the list item that contains the "Remove" button
    const li = event.target.closest("li");
    li.parentNode.removeChild(li);
  }
});
