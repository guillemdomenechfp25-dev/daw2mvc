document.addEventListener("DOMContentLoaded", () => {
    const resumenEl = document.getElementById("resumen-producto");
    const listaExtrasEl = document.getElementById("lista-extras-resumen");
    const sinIvaEl = document.getElementById("resumen-sin-iva");
    const ivaEl = document.getElementById("resumen-iva");
    const totalEl = document.getElementById("precio-total");

    const bloqueIng = document.querySelector(".bloque-ingredientes");
    const modo = bloqueIng ? bloqueIng.dataset.modo : "burger";
    const maxTotalPostre = bloqueIng ? parseInt(bloqueIng.dataset.maxTotal, 10) || 2 : 2;

    const cantidadProdInput = document.getElementById("cantidad-producto");
    const btnProdMenos = document.getElementById("btn-prod-menos");
    const btnProdMas = document.getElementById("btn-prod-mas");
    const itemsIngredientes = document.querySelectorAll(".ingrediente-item");

    if (!resumenEl) return;

    const precioBase = parseFloat(resumenEl.dataset.precioBase) || 0;

    const formatearEuro = (valor) => {
        return valor.toLocaleString("es-ES", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + " €";
    };

    function getTotalBolasPostre() {
        let suma = 0;
        itemsIngredientes.forEach((item) => {
            const input = item.querySelector(".input-cantidad-ing");
            suma += parseInt(input.value, 10) || 0;
        });
        return suma;
    }

    function actualizarResumen() {
        let precioUnitario = precioBase;
        let htmlExtras = "";
        let salsasGratisRestantes = (modo === "complemento") ? 1 : 0;

        itemsIngredientes.forEach((item) => {
            const nombre = item.dataset.nombre;
            const base = parseInt(item.dataset.base, 10) || 0;
            const precioExtra = parseFloat(item.dataset.precio) || 0;
            const input = item.querySelector(".input-cantidad-ing");
            const cantidad = parseInt(input.value, 10) || 0;

            if (cantidad !== base) {
                item.classList.add("modificado");
            } else {
                item.classList.remove("modificado");
            }

            // Modo postres (Helado Born: máx 2 bolas incluidas)
            if (modo === "postre") {
                if (cantidad > 0) {
                    const etiqueta = cantidad > 1 ? `${nombre} (x${cantidad})` : nombre;
                    htmlExtras += `
                        <div class="resumen-linea">
                            <span>${etiqueta}</span>
                            <span>0,00 €</span>
                        </div>
                    `;
                }
                return;
            }

            // Modo complementos (1ª salsa gratis, resto extra)
            if (modo === "complemento") {
                if (cantidad > 0) {
                    let salsasDePago = cantidad;
                    if (salsasGratisRestantes > 0) {
                        const descuento = Math.min(salsasGratisRestantes, salsasDePago);
                        salsasDePago -= descuento;
                        salsasGratisRestantes -= descuento;
                    }
                    const costeSalsa = salsasDePago * precioExtra;
                    precioUnitario += costeSalsa;

                    const etiqueta = cantidad > 1 ? `Salsa ${nombre} (x${cantidad})` : `Salsa ${nombre}`;
                    htmlExtras += `
                        <div class="resumen-linea">
                            <span>${etiqueta}</span>
                            <span>${costeSalsa === 0 ? "Gratis" : formatearEuro(costeSalsa)}</span>
                        </div>
                    `;
                }
                return;
            }

            // Modo burger
            if (cantidad > base) {
                const extras = cantidad - base;
                const costeExtra = extras * precioExtra;
                precioUnitario += costeExtra;

                const etiqueta = extras > 1 ? `${nombre} (x${extras})` : nombre;
                htmlExtras += `
                    <div class="resumen-linea">
                        <span>+ ${etiqueta}</span>
                        <span>${formatearEuro(costeExtra)}</span>
                    </div>
                `;
            } else if (base > 0 && cantidad === 0) {
                htmlExtras += `
                    <div class="resumen-linea text-muted-custom">
                        <span>Sin ${nombre}</span>
                        <span>0,00 €</span>
                    </div>
                `;
            }
        });

        if (htmlExtras === "") {
            listaExtrasEl.innerHTML = `
                <div class="resumen-linea text-muted-custom">
                    <span>Sin modificaciones</span>
                    <span>0,00 €</span>
                </div>
            `;
        } else {
            listaExtrasEl.innerHTML = htmlExtras;
        }

        const unidades = parseInt(cantidadProdInput.value, 10) || 1;
        const totalConIva = precioUnitario * unidades;
        const baseSinIva = totalConIva / 1.10;
        const cuotaIva = totalConIva - baseSinIva;

        sinIvaEl.textContent = formatearEuro(baseSinIva);
        ivaEl.textContent = formatearEuro(cuotaIva);
        totalEl.textContent = formatearEuro(totalConIva);
    }

    itemsIngredientes.forEach((item) => {
        const btnMenos = item.querySelector(".btn-ing-menos");
        const btnMas = item.querySelector(".btn-ing-mas");
        const input = item.querySelector(".input-cantidad-ing");
        const min = parseInt(item.dataset.min, 10) || 0;
        const max = parseInt(item.dataset.max, 10) || 4;

        btnMenos.addEventListener("click", () => {
            const actual = parseInt(input.value, 10) || 0;
            if (actual > min) {
                input.value = actual - 1;
                actualizarResumen();
            }
        });

        btnMas.addEventListener("click", () => {
            const actual = parseInt(input.value, 10) || 0;
            if (modo === "postre" && getTotalBolasPostre() >= maxTotalPostre) {
                return;
            }
            if (actual < max) {
                input.value = actual + 1;
                actualizarResumen();
            }
        });
    });

    btnProdMenos.addEventListener("click", () => {
        const actual = parseInt(cantidadProdInput.value, 10) || 1;
        if (actual > 1) {
            cantidadProdInput.value = actual - 1;
            actualizarResumen();
        }
    });

    btnProdMas.addEventListener("click", () => {
        const actual = parseInt(cantidadProdInput.value, 10) || 1;
        if (actual < 20) {
            cantidadProdInput.value = actual + 1;
            actualizarResumen();
        }
    });

    actualizarResumen();
});