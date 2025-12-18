const search = document.getElementById("search");

search.addEventListener("keydown", (e) => {
    if (e.keyCode === 13) {
        e.preventDefault(); // Prevent form submission
    }
});

search.addEventListener("keyup", () => {
    const value = search.value;

    $.ajax({
        type: "GET",
        url: "/search",
        data: {
            _token: $("input[name=_token]").val(),
            search: value,
        },
        success: function (data) {
            let transactionContainer = document.querySelector(
                ".transaction-container"
            );
            transactionContainer.innerHTML = ""; // Clear previous results

            if (data.length === 0) {
                transactionContainer.innerHTML =
                    '<span class="mt-10 text-md font-semibold text-center text-gray-500 w-full">No transactions found</span>';
            } else {
                data.forEach((transaction) => {
                    transactionContainer.innerHTML += `
                        <a href="expense/${transaction.id}">
                            <div class="border-solid border-2 border-[#EEEEEE] px-4 py-2 rounded-2xl">
                                <div class="flex justify-between items-center">
                                    <div class="">
                                        <h1 class="text-md lg:text-lg font-bold mb-2">${
                                            transaction.expense
                                        }</h1>
                                        <span class="text-sm lg:text-md font-semibold">${new Date(
                                            transaction.date
                                        ).toLocaleDateString("en-GB", {
                                            day: "2-digit",
                                            month: "short",
                                            year: "numeric",
                                        })}</span>
                                    </div>
                                    <span class="text-right text-md lg:text-xl font-bold">${new Intl.NumberFormat(
                                        "de-DE"
                                    ).format(transaction.total)}</span>
                                </div>
                            </div>
                        </a>`;
                });
            }
        },
    });
});
