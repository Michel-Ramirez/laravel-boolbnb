const form = document.getElementById("payment-form");
const submit = document.querySelector('input[type="submit"]');
const nonceInput = document.getElementById("nonce");

braintree.client.create(
    {
        authorization: "sandbox_pg6z6szp_hdxcm33stggcsyyg",
    },
    function (clientErr, clientInstance) {
        if (clientErr) {
            console.error(clientErr);
            return;
        }

        // This example shows Hosted Fields, but you can also use this
        // client instance to create additional components here, such as
        // PayPal or Data Collector.

        braintree.hostedFields.create(
            {
                client: clientInstance,
                styles: {
                    input: {
                        "font-size": "14px",
                    },
                    "input.invalid": {
                        color: "red",
                    },
                    "input.valid": {
                        color: "green",
                    },
                },
                fields: {
                    number: {
                        container: "#card-number",
                        placeholder: "4111 1111 1111 1111",
                    },
                    cvv: {
                        container: "#cvv",
                        placeholder: "123",
                    },
                    expirationDate: {
                        container: "#card-expiration",
                        placeholder: "10/2022",
                    },
                    cardholderName: {
                        container: "#card-name",
                        placeholder: "Mario Rossi",
                    },
                },
            },
            function (hostedFieldsErr, hostedFieldsInstance) {
                if (hostedFieldsErr) {
                    console.error(hostedFieldsErr);
                    return;
                }

                submit.removeAttribute("disabled");

                form.addEventListener(
                    "submit",
                    function (event) {
                        event.preventDefault();

                        hostedFieldsInstance.tokenize(function (
                            tokenizeErr,
                            payload
                        ) {
                            if (tokenizeErr) {
                                console.error(tokenizeErr);
                                return;
                            }
                            nonceInput.value = payload.nonce;
                            form.submit();
                        });
                    },
                    false
                );
            }
        );
    }
);
