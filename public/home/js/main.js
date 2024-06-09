const BASEURL = "https://sis.elreyfastcruise.com/";

$(document).ready(function () {
    let stepsWizard = $("#wizard").steps({
        headerTag: "h4",
        bodyTag: "section",
        transitionEffect: "fade",
        enableAllSteps: true,
        transitionEffectSpeed: 300,
        enablePagination: false,
        labels: {
            next: "Next",
            previous: "Back",
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            let from = $("#from").val();
            let to = $("#to").val();
            let quantity__input1 = $("#quantity__input1").val();
            let quantity__input2 = $("#quantity__input2").val();
            let quantity__input3 = $("#quantity__input3").val();
            let categori = $("#categori").val();
            let dateGo = $("#dateGo").val();
            let dataReturn = $("#dataReturn").val();
            let nameFisrt = $("#nameFisrt").val();
            let nameLast = $("#nameLast").val();
            let agentName = $("#gsearchsimple").val();
            let email = $("#email").val();
            let wa = $("#wa").val();
            let passagerChildren = $("#passagerChildren").val();
            let passagerAdult = $("#passagerAdult").val();
            let statusReturnDate = $("#status").val();
            let stG = "";

            $("#notifTransport").removeClass("d-block");

            sessionStorage.setItem("adult", quantity__input1);
            sessionStorage.setItem("children", quantity__input2);
            sessionStorage.setItem("infant", quantity__input3);
            // console.log(newIndex);
            $("#next").html("NEXT");
            if (newIndex == 8) {
                // return true;
            } else if (newIndex == 7) {
                $("#back").attr("data-id", "");
                $("#next").removeClass("d-nonIm");

                $("#Dfrom").html(from);
                $("#Dto").html(to);
                if (nameFisrt === undefined) {
                    $("#Dorder").html(agentName);
                } else {
                    $("#Dorder").html(nameFisrt + " " + nameLast);
                }

                $("#Demail").html(email);
                $("#Dphone").html(wa);
                $("#DjadwalGO").html(dateGo);
                $("#DjadwalReturn").html(dataReturn);
                $("#categoryPassaanger").html(categori);
                $("#categoryPassaangerM").html(categori);
                $("#DjadwalDepartM").html(dateGo);
                $("#DjadwalreturnM").html(dataReturn);
                let totalPenumpang =
                    parseInt(quantity__input1) +
                    parseInt(
                        quantity__input2 !== ""
                            ? parseInt(quantity__input2)
                            : 0,
                    );

                // DATASEAT
                var dataseat = [];
                $(".seatcheck:checkbox:checked").each(function (i) {
                    dataseat[i] = $(this).val();
                });

                $("#DpricePasseger").html("");
                $("#DpricePassegerM").html("");
                var subtotal = 0;
                var subtotalReturn = 0;

                //GO
                let idKapal = $("#idKapal").val();
                $.ajax({
                    async: false,
                    type: "POST", // Metode pengiriman data menggunakan POST
                    url: BASEURL + "home/getBoat", // File yang akan memproses data
                    data: {
                        idKapal: idKapal,
                    }, // Data yang akan dikirim ke file pemroses
                    success: function (response) {
                        // Jika berhasil
                        var res = JSON.parse(response);
                        // console.log(res);
                        $("#Dboat").html(res.boatName + " (" + res.sTime + ")");
                        $("#DboatM").html(
                            res.boatName + " (" + res.sTime + ")",
                        );

                        var harga = [];
                        var harga_child = [];
                        var totalHarga = 0;
                        var splitSeat = [];
                        for (let i = 0; i < dataseat.length; i++) {
                            if (i <= parseInt(quantity__input1) - 1) {
                                let splitOne = dataseat[i].split("_");

                                splitSeat[i] = splitOne[0];

                                if (categori == "international") {
                                    if (splitOne[1] == "V") {
                                        harga.push(res.priceInternationalVIP);
                                        totalHarga += parseInt(
                                            res.priceInternationalVIPAsli,
                                        );
                                    } else {
                                        harga.push(res.priceInternational);
                                        totalHarga += parseInt(
                                            res.priceInternationalAsli,
                                        );
                                    }
                                } else {
                                    if (splitOne[1] == "V") {
                                        harga.push(res.priceDomesticVIP);
                                        totalHarga += parseInt(
                                            res.priceDomesticVIPAsli,
                                        );
                                    } else {
                                        harga.push(res.priceDomestic);
                                        totalHarga += parseInt(
                                            res.priceDomesticAsli,
                                        );
                                    }
                                }
                            } else {
                                let splitOne = dataseat[i].split("_");

                                splitSeat[i] = splitOne[0];

                                if (categori == "international") {
                                    if (splitOne[1] == "V") {
                                        harga_child.push(
                                            res.child_priceInternationalVIP,
                                        );
                                        totalHarga += parseInt(
                                            res.child_priceInternationalVIPAsli,
                                        );
                                    } else {
                                        harga_child.push(
                                            res.child_priceInternational,
                                        );
                                        totalHarga += parseInt(
                                            res.child_priceInternationalAsli,
                                        );
                                    }
                                } else {
                                    console.log("adasd");
                                    if (splitOne[1] == "V") {
                                        harga_child.push(
                                            res.child_priceDomesticVIP,
                                        );
                                        totalHarga += parseInt(
                                            res.child_priceDomesticVIPAsli,
                                        );
                                    } else {
                                        harga_child.push(
                                            res.child_priceDomestic,
                                        );
                                        totalHarga += parseInt(
                                            res.child_priceDomesticAsli,
                                        );
                                    }
                                }
                            }
                        }

                        document.getElementById("Dseat").innerHTML = splitSeat;
                        document.getElementById("DseatM").innerHTML = splitSeat;

                        subtotal = totalHarga;

                        let hargaUnic = [...new Set(harga)];
                        let hargaUnic_child = [...new Set(harga_child)];
                        let strChild = " ";
                        if (parseInt(quantity__input2) > 0) {
                            strChild =
                                quantity__input2 +
                                " x (" +
                                hargaUnic_child +
                                ") GO <br>";
                        } else {
                            strChild = " ";
                        }
                        $("#DpricePasseger").append(
                            quantity__input1 + " x (" + hargaUnic + ") GO <br>",
                            strChild,
                        );
                        $("#DpricePassegerM").append(
                            totalPenumpang + " x (" + hargaUnic + ") GO <br>",
                            strChild,
                        );
                    },
                });

                // DATASEAT RETURN
                var dataseatReturn = [];
                $(".seatReturnCheck:checkbox:checked").each(function (i) {
                    dataseatReturn[i] = $(this).val();
                });

                let oneways = $("#status").val();
                let idKapalReturn = 0;
                if (oneways == "true") {
                    idKapalReturn = $("#idKapalReturn").val();
                } else {
                    // set null
                    document.getElementById("DseatReturn").innerHTML = "-";
                    document.getElementById("DseatReturnM").innerHTML = "-";
                    $("#load_seat_return_2").html("");
                    $("#load_seat_regular_return").html("");
                }

                // RETURN
                if (idKapalReturn !== 0) {
                    $.ajax({
                        async: false,
                        type: "POST", // Metode pengiriman data menggunakan POST
                        url: BASEURL + "home/getBoat", // File yang akan memproses data
                        data: {
                            idKapal: idKapalReturn,
                        }, // Data yang akan dikirim ke file pemroses
                        success: function (response) {
                            // Jika berhasil
                            var res = JSON.parse(response);
                            $("#DboatReturnM").html(
                                res.boatName + " (" + res.sTime + ")",
                            );
                            $("#DboatReturn").html(
                                res.boatName + " (" + res.sTime + ")",
                            );

                            var harga = [];
                            var totalReturn = 0;
                            var splitSeatReturn = [];
                            var harga_child = [];

                            for (let i = 0; i < dataseatReturn.length; i++) {
                                if (i <= parseInt(quantity__input1) - 1) {
                                    let splitOne = dataseatReturn[i].split("_");
                                    splitSeatReturn[i] = splitOne[0];
                                    if (categori == "international") {
                                        if (splitOne[1] == "V") {
                                            harga.push(
                                                res.priceInternationalVIP,
                                            );
                                            totalReturn += parseInt(
                                                res.priceInternationalVIPAsli,
                                            );
                                        } else {
                                            harga.push(res.priceInternational);
                                            totalReturn += parseInt(
                                                res.priceInternationalAsli,
                                            );
                                        }
                                    } else {
                                        if (splitOne[1] == "V") {
                                            harga.push(res.priceDomesticVIP);
                                            totalReturn += parseInt(
                                                res.priceDomesticVIPAsli,
                                            );
                                        } else {
                                            harga.push(res.priceDomestic);
                                            totalReturn += parseInt(
                                                res.priceDomesticAsli,
                                            );
                                        }
                                    }
                                } else {
                                    let splitOne = dataseatReturn[i].split("_");
                                    splitSeatReturn[i] = splitOne[0];

                                    if (categori == "international") {
                                        if (splitOne[1] == "V") {
                                            harga_child.push(
                                                res.child_priceInternationalVIP,
                                            );
                                            totalReturn += parseInt(
                                                res.child_priceInternationalVIPAsli,
                                            );
                                        } else {
                                            harga_child.push(
                                                res.child_priceInternational,
                                            );
                                            totalReturn += parseInt(
                                                res.child_priceInternationalAsli,
                                            );
                                        }
                                    } else {
                                        console.log("adasd");
                                        if (splitOne[1] == "V") {
                                            harga_child.push(
                                                res.child_priceDomesticVIP,
                                            );
                                            totalReturn += parseInt(
                                                res.child_priceDomesticVIPAsli,
                                            );
                                        } else {
                                            harga_child.push(
                                                res.child_priceDomestic,
                                            );
                                            totalReturn += parseInt(
                                                res.child_priceDomesticAsli,
                                            );
                                        }
                                    }
                                }
                            }

                            if (oneways == "true") {
                                document.getElementById(
                                    "DseatReturn",
                                ).innerHTML = splitSeatReturn;
                                document.getElementById(
                                    "DseatReturnM",
                                ).innerHTML = splitSeatReturn;
                            }

                            subtotalReturn = totalReturn;

                            let hargaUnic = [...new Set(harga)];
                            let hargaUnic_child = [...new Set(harga_child)];
                            if (parseInt(quantity__input2) > 0) {
                                strChild =
                                    quantity__input2 +
                                    " x (" +
                                    hargaUnic_child +
                                    ") Return <br>";
                            } else {
                                strChild = " ";
                            }
                            $("#DpricePasseger").append(
                                quantity__input1 +
                                    " x (" +
                                    hargaUnic +
                                    ") Return <br>",
                                strChild,
                            );
                            $("#DpricePassegerM").append(
                                totalPenumpang +
                                    " x (" +
                                    hargaUnic +
                                    ") Return <br>",
                                strChild,
                            );
                        },
                    });
                } else {
                    $("#DboatReturnM").html("-");
                    $("#DboatReturn").html("-");
                    $("#DjadwalReturn").html("~");
                    $("#DjadwalreturnM").html("~");
                }

                // TRANSPORT
                let idTransport = $("input[name='transport']:checked").val();

                $.ajax({
                    async: false,
                    type: "POST", // Metode pengiriman data menggunakan POST
                    url: BASEURL + "home/getTransport", // File yang akan memproses data
                    data: {
                        idKapal: idTransport,
                    }, // Data yang akan dikirim ke file pemroses
                    success: function (response) {
                        // Jika berhasil
                        if (idTransport !== undefined) {
                            var res = JSON.parse(response);
                            $("#Dtransport").html(res.transpotName);
                            $("#DtransportM").html(res.transpotName);
                            $("#DpriceTransport").html(
                                totalPenumpang + " x (" + res.price + ")",
                            );
                            $("#DpriceTransportM").html(
                                totalPenumpang + " x (" + res.price + ")",
                            );
                            subtotal +=
                                parseInt(res.priceAsli) *
                                parseInt(totalPenumpang);
                        } else {
                            $("#Dtransport").html("-");
                            $("#DtransportM").html("-");
                            $("#DpriceTransport").html("-");
                            $("#DpriceTransportM").html("-");
                        }
                    },
                });

                // $('#Djadwal').html($("input[name='optradio']:checked").val();)

                // PASSAGER
                $("#Dpassager").html("");
                $("#DpassagerM").html("");
                for (let index = 1; index <= quantity__input1; index++) {
                    $("#Dpassager").append(
                        `<li>` + $("#passagerAdult" + index).val() + `</li>`,
                    );
                    $("#DpassagerM").append(
                        `<li>` + $("#passagerAdult" + index).val() + `</li>`,
                    );
                }

                for (let index = 1; index <= quantity__input2; index++) {
                    $("#Dpassager").append(
                        `<li>` + $("#passagerChildren" + index).val() + `</li>`,
                    );
                    $("#DpassagerM").append(
                        `<li>` + $("#passagerChildren" + index).val() + `</li>`,
                    );
                }

                for (let index = 1; index <= quantity__input3; index++) {
                    $("#Dpassager").append(
                        `<li>` + $("#passagerInfant" + index).val() + `</li>`,
                    );
                    $("#DpassagerM").append(
                        `<li>` + $("#passagerInfant" + index).val() + `</li>`,
                    );
                }

                let diskon_agent = $("#agent_user").val();
                let diskon_agent_internasional = $(
                    "#agent_user_internasional",
                ).val();
                let diskon_new = 0;

                let grandTotal = 0;

                if (categori == "international") {
                    if (diskon_agent_internasional <= 100) {
                        diskon_new = diskon_agent_internasional / 100;
                        grandTotal =
                            subtotal +
                            subtotalReturn -
                            (subtotal + subtotalReturn) *
                                parseFloat(diskon_new);
                    } else {
                        if (idKapalReturn !== 0) {
                            diskon_new = diskon_agent_internasional * 2;
                        } else {
                            diskon_new = diskon_agent_internasional;
                        }
                        grandTotal = subtotal + subtotalReturn - diskon_new;
                    }
                } else {
                    if (diskon_agent <= 100) {
                        diskon_new = diskon_agent / 100;
                        grandTotal =
                            subtotal +
                            subtotalReturn -
                            (subtotal + subtotalReturn) *
                                parseFloat(diskon_new);
                    } else {
                        if (idKapalReturn !== 0) {
                            diskon_new = diskon_agent * 2;
                        } else {
                            diskon_new = diskon_agent;
                        }
                        grandTotal = subtotal + subtotalReturn - diskon_new;
                    }
                }

                $("#totalBayarM").html(
                    "Rp " + grandTotal.toLocaleString("en-US"),
                );
                $("#totalBayar").html(
                    "Rp " + grandTotal.toLocaleString("en-US"),
                );
                $("#subTotal").val(grandTotal);

                if ($("input[name='transport']:checked").length > 1) {
                    $("#notifTransport").addClass("d-block");
                    $("#notifTransport").html(
                        "Please choose not to choose a transport more than 1",
                    );
                } else {
                    $("#notifTransport").removeClass("d-block");
                    $("#next").attr("data-id", "0");
                    $("#next").html("Finish");
                    return true;
                }
            } else if (newIndex == 6) {
                $("#next").attr("data-id", "");
                $("#back").attr("data-id", "");

                let adultInput = sessionStorage.getItem("adult");
                let childrenInput = sessionStorage.getItem("children");
                let infantInput = sessionStorage.getItem("infant");

                if (
                    nameFisrt == "" ||
                    nameLast == "" ||
                    email == "" ||
                    wa == ""
                ) {
                    if (statusReturnDate == "false") {
                        $("#back").attr("data-id", "2");
                    }
                    if (nameFisrt == "") {
                        $("#nameFisrt").addClass("error-input");
                        $(".alert-form-7").addClass("d-block");
                    } else {
                        $("#nameFisrt").removeClass("error-input");
                        $(".alert-form-7").removeClass("d-block");
                    }

                    if (nameLast == "") {
                        $("#nameLast").addClass("error-input");
                        $(".alert-form-8").addClass("d-block");
                    } else {
                        $("#nameLast").removeClass("error-input");
                        $(".alert-form-8").removeClass("d-block");
                    }

                    if (email == "") {
                        $("#email").addClass("error-input");
                        $(".alert-form-9").addClass("d-block");
                    } else {
                        $("#email").removeClass("error-input");
                        $(".alert-form-9").removeClass("d-block");
                    }

                    if (wa == "") {
                        $("#wa").addClass("error-input");
                        $(".alert-form-10").addClass("d-block");
                    } else {
                        $("#wa").removeClass("error-input");
                        $(".alert-form-10").removeClass("d-block");
                    }
                } else {
                    $("#nameFisrt").removeClass("error-input");
                    $(".alert-form-7").removeClass("d-block");
                    $("#nameLast").removeClass("error-input");
                    $(".alert-form-8").removeClass("d-block");
                    $("#email").removeClass("error-input");
                    $(".alert-form-9").removeClass("d-block");
                    $("#wa").removeClass("error-input");
                    $(".alert-form-10").removeClass("d-block");
                    $("#passagerAdult").removeClass("error-input");
                    $(".alert-form-11").removeClass("d-block");
                    $("#passagerChildren").removeClass("error-input");
                    $(".alert-form-12").removeClass("d-block");

                    for (let i = 1; i <= adultInput; i++) {
                        if ($("#passagerAdult" + i).val() == "") {
                            $("#passagerAdult" + i).addClass("error-input");
                            $(".alert-form-11" + i).addClass("d-block");
                            if (statusReturnDate == "false") {
                                $("#back").attr("data-id", "2");
                            }
                            return false;
                        } else if ($("#ageAdult" + i).val() == "") {
                            $("#ageAdult" + i).addClass("error-input");
                            $(".alert-form-12" + i).addClass("d-block");
                            if (statusReturnDate == "false") {
                                $("#back").attr("data-id", "2");
                            }
                            return false;
                        } else {
                            $("#passagerAdult" + i).removeClass("error-input");
                            $(".alert-form-11" + i).removeClass("d-block");
                            $("#ageAdult" + i).removeClass("error-input");
                            $(".alert-form-12" + i).removeClass("d-block");
                        }
                    }

                    for (let i = 1; i <= childrenInput; i++) {
                        if ($("#passagerChildren" + i).val() == "") {
                            $("#passagerChildren" + i).addClass("error-input");
                            $(".alert-form-child" + i).addClass("d-block");
                            if (statusReturnDate == "false") {
                                $("#back").attr("data-id", "2");
                            }
                            return false;
                        } else if ($("#ageChildren" + i).val() == "") {
                            $("#ageChildren" + i).addClass("error-input");
                            $(".alert-form-child-2" + i).addClass("d-block");
                            if (statusReturnDate == "false") {
                                $("#back").attr("data-id", "2");
                            }
                            return false;
                        } else {
                            $("#passagerChildren" + i).removeClass(
                                "error-input",
                            );
                            $(".alert-form-child" + i).removeClass("d-block");
                            $("#ageChildren" + i).removeClass("error-input");
                            $(".alert-form-child-2" + i).removeClass("d-block");
                        }
                    }

                    for (let i = 1; i <= infantInput; i++) {
                        if ($("#passagerInfant" + i).val() == "") {
                            $("#passagerInfant" + i).addClass("error-input");
                            $(".alert-form-Infant" + i).addClass("d-block");
                            if (statusReturnDate == "false") {
                                $("#back").attr("data-id", "2");
                            }
                            return false;
                        } else {
                            $("#passagerInfant" + i).removeClass("error-input");
                            $(".alert-form-Infant" + i).removeClass("d-block");
                        }
                    }

                    return true;
                }
            } else if (newIndex == 5) {
                $("#next").removeClass("d-nonIm");
                $("#next").attr("data-id", "");
                let dateR = $("#id_dateReturn").val();
                if (dateR == "") {
                    // $('#next').attr("data-id", "3");
                    $("#back").attr("data-id", "2");
                    return true;
                } else {
                    let totalPenumpang =
                        parseInt(quantity__input1) +
                        parseInt(
                            quantity__input2 !== ""
                                ? parseInt(quantity__input2)
                                : 0,
                        );
                    let jumlahSelectSeat = $(
                        'input[name="seatReturn[]"]:checked+label',
                    ).length;

                    if (jumlahSelectSeat > totalPenumpang) {
                        $("#notifSeatReturn").addClass("d-block");
                        $("#notifSeatReturn").html(
                            "Please choose a seat no more than " +
                                totalPenumpang +
                                " passengers",
                        );
                    } else if (
                        jumlahSelectSeat <= 0 ||
                        jumlahSelectSeat < totalPenumpang
                    ) {
                        $("#notifSeatReturn").addClass("d-block");
                        $("#notifSeatReturn").html(
                            "Please choose a seat for " +
                                totalPenumpang +
                                " passengers",
                        );
                    } else {
                        $("#notifSeatReturn").removeClass("d-block");
                        $("#notifSeatReturn").html("");
                        $("#next").removeClass("d-nonIm");
                        return true;
                    }
                }
            } else if (newIndex == 4) {
                $("#next").attr("data-id", "");
                $("#back").attr("data-id", "");
                // $("#next").removeClass("d-nonIm");
                $("#next").addClass("d-nonIm");
                let dateR = $("#id_dateReturn").val();
                if (dateR !== "") {
                    $("#seat_go").addClass("d-nonIm");
                    $("#seat_return").removeClass("d-nonIm");
                }

                return true;
            } else if (newIndex == 3) {
                $("#next").addClass("d-nonIm");

                let totalPenumpang =
                    parseInt(quantity__input1) +
                    parseInt(quantity__input2 !== "" ? quantity__input2 : 0);
                let jumlahSelectSeat = $(
                    'input[name="seat[]"]:checked+label',
                ).length;

                if (jumlahSelectSeat > totalPenumpang) {
                    $("#notifSeat").addClass("d-block");
                    $("#notifSeat").html(
                        "Please choose a seat no more than " +
                            totalPenumpang +
                            " passengers",
                    );
                } else if (
                    jumlahSelectSeat <= 0 ||
                    jumlahSelectSeat < totalPenumpang
                ) {
                    $("#notifSeat").addClass("d-block");
                    $("#notifSeat").html(
                        "Please choose a seat for " +
                            totalPenumpang +
                            " passengers",
                    );
                } else {
                    $("#notifSeat").removeClass("d-block");
                    $("#notifSeat").html("");
                    return true;
                }
            } else if (newIndex == 2) {
                let dateR = $("#id_dateReturn").val();
                // console.log(dateR);
                if (dateR == "") {
                    $("#next").attr("data-id", "3");
                    // $('#back').attr("data-id", "2");
                }
                let jadwal = $(".bokingCheked");
                if (jadwal == undefined) {
                    $("#notif").addClass("d-block");
                } else {
                    let adultInput = sessionStorage.getItem("adult");
                    let childrenInput = sessionStorage.getItem("children");
                    let infantInput = sessionStorage.getItem("infant");
                    $("#passegerInput").html("");
                    for (let i = 1; i <= adultInput; i++) {
                        $("#passegerInput").append(
                            `<div class="form-row">
                                                        <div class="form-col">
                                                            <label for="">
                                                                Name Passenger Adult ` +
                                i +
                                `
                                                            </label>
                                                            <div class="form-holder">
                                                                <i class="fas fa-user"></i>
                                                                <input type="text" class="form-control" id="passagerAdult` +
                                i +
                                `" name="passagerAdult[]" required>
                                                            </div>
                                                            <div class="invalid-feedback alert-form-11` +
                                i +
                                `">
                                                                Please Complete The Data !!
                                                            </div>
                                                        </div>
                                                        <div class="form-col">
                                                            <label for="">
												Passenger Passport/KTP ` +
                                i +
                                `
                                                            </label>
                                                            <div class="form-holder password">
													<i class="fas fa-passport"></i>
                                                                 <input type="number" class="form-control" id="ageAdult` +
                                i +
                                `" name="PassagerID[]" required>
                                                            </div>
                                                            <div class="invalid-feedback alert-form-12` +
                                i +
                                `">
                                                                Please Complete The Data !!
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <hr>`,
                        );
                    }

                    for (let i = 1; i <= childrenInput; i++) {
                        $("#passegerInput").append(
                            ` <div class="form-row">
                                                        <div class="form-col">
                                                            <label for="">
                                                                Name Passenger Children ` +
                                i +
                                `
                                                            </label>
                                                            <div class="form-holder">
                                                                <i class="fas fa-child"></i>
                                                                <input type="text" class="form-control" id="passagerChildren` +
                                i +
                                `" name="passagerChildren[]" required>
                                                            </div>
                                                            <div class="invalid-feedback alert-form-child` +
                                i +
                                `">
                                                                Please Complete The Data !!
                                                            </div>
                                                        </div>
                                                        <div class="form-col">
                                                            <label for="">
                                                            Passenger Passport/KTP ` +
                                i +
                                `
                                                            </label>
                                                            <div class="form-holder password">
                                                            <i class="fas fa-passport"></i>
                                                                <input type="number" class="form-control" id="ageChildren` +
                                i +
                                `" name="PassagerID[]" value="0" readonly>
                                                            </div>
                                                            <div class="invalid-feedback alert-form-child-2` +
                                i +
                                `">
                                                                Please Complete The Data !!
                                                            </div>
                                                        </div>
                                                    </div> <hr>`,
                        );
                    }

                    for (let i = 1; i <= infantInput; i++) {
                        $("#passegerInput").append(
                            ` <div class="form-row">
                                                        <div class="form-col">
                                                            <label for="">
                                                                Name Passenger Infant ` +
                                i +
                                `
                                                            </label>
                                                            <div class="form-holder">
                                                                <i class="fas fa-child"></i>
                                                                <input type="text" class="form-control" id="passagerInfant` +
                                i +
                                `" name="passagerInfant[]" required>
                                                            </div>
                                                            <div class="invalid-feedback alert-form-Infant` +
                                i +
                                `">
                                                                Please Complete The Data !!
                                                            </div>
                                                        </div>
                                                    </div> <hr>`,
                        );
                    }

                    $("#notif").removeClass("d-block");

                    return true;
                }
            } else if (newIndex == 1) {
                // cek date return in active

                let dateReturn = "kosong";
                if (statusReturnDate == "true") {
                    dateReturn = $("#dataReturn").val();
                }

                var statusSchedule = "";

                $("#next").attr("data-id", "");
                $("#back").attr("data-id", "");
                $("#passegerInput").html("");

                if (
                    from == null ||
                    to == null ||
                    quantity__input1 == 0 ||
                    categori == null ||
                    dateGo == "" ||
                    dateReturn == ""
                ) {
                    if (from == null) {
                        $("#from").addClass("error-input");
                        $(".alert-form-1").addClass("d-block");
                    } else {
                        $("#from").removeClass("error-input");
                        $(".alert-form-1").removeClass("d-block");
                    }
                    if (to == null) {
                        $("#to").addClass("error-input");
                        $(".alert-form-2").addClass("d-block");
                    } else {
                        $("#to").removeClass("error-input");
                        $(".alert-form-2").removeClass("d-block");
                    }

                    if (quantity__input1 == 0) {
                        $("#quantity__input1").addClass("error-input");
                        $(".alert-form-3").addClass("d-block");
                    } else {
                        $("#quantity__input1").removeClass("error-input");
                        $(".alert-form-3").removeClass("d-block");
                    }

                    if (categori == null) {
                        $("#categori").addClass("error-input");
                        $(".alert-form-4").addClass("d-block");
                    } else {
                        $("#categori").removeClass("error-input");
                        $(".alert-form-4").removeClass("d-block");
                    }

                    if (dateGo == "") {
                        $("#dateGo").addClass("error-input");
                        $(".alert-form-5").addClass("d-block");
                    } else {
                        $("#dateGo").removeClass("error-input");
                        $(".alert-form-5").removeClass("d-block");
                    }

                    if (dataReturn == "") {
                        $("#dataReturn").addClass("error-input");
                        $(".alert-form-6").addClass("d-block");
                        $(".alert-form-6").html("Please Complete The Data !!");
                    } else {
                        $("#dataReturn").removeClass("error-input");
                        $(".alert-form-6").removeClass("d-block");
                        $(".alert-form-6").html("");
                    }
                } else {
                    if (dateReturn < dateGo) {
                        $("#dataReturn").addClass("error-input");
                        $(".alert-form-6").addClass("d-block");
                        $(".alert-form-6").html(
                            "Please don't choose a return date smaller than the departure date",
                        );
                    } else {
                        $("#from").removeClass("error-input");
                        $(".alert-form-1").removeClass("d-block");
                        $("#to").removeClass("error-input");
                        $(".alert-form-2").removeClass("d-block");
                        $("#quantity__input1").removeClass("error-input");
                        $(".alert-form-3").removeClass("d-block");
                        $("#categori").removeClass("error-input");
                        $(".alert-form-4").removeClass("d-block");
                        $("#dateGo").removeClass("error-input");
                        $(".alert-form-5").removeClass("d-block");
                        $("#dataReturn").removeClass("error-input");
                        $(".alert-form-6").removeClass("d-block");
                        // cekshedule

                        if (dateGo) {
                            $.ajax({
                                async: false,
                                type: "POST", // Metode pengiriman data menggunakan POST
                                url: BASEURL + "home/cekSchedule", // File yang akan memproses data
                                data: {
                                    dateGo: dateGo,
                                    to: to,
                                    from: from,
                                }, // Data yang akan dikirim ke file pemroses
                                success: function (response) {
                                    var res = JSON.parse(response);
                                    if (res == "0") {
                                        Swal.fire({
                                            title: "Sorry",
                                            text: "Departure Schedule Not Found",
                                            icon: "error",
                                            confirmButtonText: "Close",
                                        });

                                        return false;
                                    } else if (
                                        res == "1" &&
                                        dateReturn == "kosong"
                                    ) {
                                        // console.log('jalan');
                                        statusSchedule = "ada";
                                    }
                                },
                            });
                            if (dateReturn !== "kosong") {
                                $.ajax({
                                    async: false,
                                    type: "POST", // Metode pengiriman data menggunakan POST
                                    url: BASEURL + "home/cekScheduleReturn", // File yang akan memproses data
                                    data: {
                                        dateReturn: dateReturn,
                                        to: to,
                                        from: from,
                                    }, // Data yang akan dikirim ke file pemroses
                                    success: function (response) {
                                        var res = JSON.parse(response);
                                        console.log(res);
                                        if (res == "-1") {
                                            Swal.fire({
                                                title: "Sorry",
                                                text: "Return Schedule Not Found",
                                                icon: "error",
                                                confirmButtonText: "Close",
                                            });
                                            return false;
                                        } else if (res == "1") {
                                            // console.log('jalan');
                                            statusSchedule = "ada";
                                        }
                                    },
                                });
                            }

                            if (statusSchedule == "ada") {
                                $("#next").addClass("d-nonIm");

                                if (categori == "domestic") {
                                    $('#wizard [name="country"]').val(
                                        "Indonesia",
                                    );
                                }

                                return true;
                            }
                        }
                    }

                    // sessionStorage.setItem('dateGo', dateGo);
                    // sessionStorage.setItem('dataReturn', dataReturn);
                }
            } else {
                $("#next").attr("data-id", "");
                $("#back").attr("data-id", "");
                $("#next").removeClass("d-nonIm");

                return true;
            }
        },
    });

    $("#next").click(function () {
        // console.log('ada');
        let btnSkip = $("#next").attr("data-id");
        if (btnSkip == "3") {
            stepsWizard.steps("next");
            stepsWizard.steps("next");
            stepsWizard.steps("next");
        } else if (btnSkip == "0") {
            let paymentMethod = $('input[name="payment"]:checked').val();

            if (paymentMethod == undefined) {
                // console.log('ada');
                $("#notifPayment").addClass("d-block");
                $("#notifPayment").html("Please choose a payment method ");
            } else {
                $("#notifPayment").removeClass("d-block");
                $("#notifPayment").html("");
                $("#loding").css("display", "flex");
                $("#list_payment").css("display", "none");
                $("#back").css("display", "none");
                $("#next").css("display", "none");

                $("#wizard").submit();
                // console.log('kosong');
            }
        } else {
            stepsWizard.steps("next");
        }
    });

    $("#back").click(function () {
        let btnBak = $("#back").attr("data-id");
        if (btnBak == "2") {
            stepsWizard.steps("previous");
            stepsWizard.steps("previous");
            stepsWizard.steps("previous");
        } else {
            stepsWizard.steps("previous");
        }
    });

    $(document).delegate(".jadwal", "click", function () {
        // $("#next").removeClass("d-nonIm");
        $(".jadwal").removeClass("bokingCheked");
        $(this).addClass("bokingCheked");

        let idJad = $(this).attr("data-id");
        let idJadwal = $(this).attr("data-jadwal");
        let dateGo = $("#dateGo").val();

        $("#idKapal").val($(this).attr("data-jadwal"));

        $.ajax({
            type: "POST", // Metode pengiriman data menggunakan POST
            url: BASEURL + "home/getSeat", // File yang akan memproses data
            data: {
                idJad: idJad,
                idJadwal: idJadwal,
                dateGo: dateGo,
            }, // Data yang akan dikirim ke file pemroses
            success: function (response) {
                // Jika berhasil
                var res = JSON.parse(response);
                $("#load_seat").html("");
                $("#load_seat_regular").html("");
                $("#lodingSeat").css("display", "block");
                $("#lodingSeat2").css("display", "block");
                setTimeout(function () {
                    $("#lodingSeat").css("display", "none");
                    $("#lodingSeat2").css("display", "none");
                    $("#load_seat").html(res.isi);
                    $("#load_seat_regular").html(res.isiR);
                }, 500);
            },
        });

        // $.ajax({
        //     type: "POST", // Metode pengiriman data menggunakan POST
        //     url: BASEURL + "home/getSeatRegular", // File yang akan memproses data
        //     data: {
        //         idJad: idJad,
        //         idJadwal: idJadwal,
        //         dateGo: dateGo,
        //     }, // Data yang akan dikirim ke file pemroses
        //     success: function (response) {
        //         // Jika berhasil
        //         var res = JSON.parse(response);
        //         $("#load_seat_regular").html(res.isi);
        //     },
        // });

        stepsWizard.steps("next");
    });

    $(document).delegate(".returnJadwal", "click", function () {
        // $("#next").removeClass("d-nonIm");
        $(".returnJadwal").removeClass("bokingCheked");
        $(this).addClass("bokingCheked");
        let idJadwal = $(this).attr("data-jadwal");
        let oneways = $("#status").val();
        idJad = $(this).attr("data-idReturn");
        let dataReturn = $("#dataReturn").val();

        $("#idKapalReturn").val($(this).attr("data-jadwal"));

        $.ajax({
            type: "POST", // Metode pengiriman data menggunakan POST
            url: BASEURL + "home/getSeatReturn", // File yang akan memproses data
            data: {
                idJad: idJad,
                idJadwal: idJadwal,
                dataReturn: dataReturn,
            }, // Data yang akan dikirim ke file pemroses
            success: function (response) {
                // Jika berhasil
                var res = JSON.parse(response);
                if (oneways == "true") {
                    $("#load_seat_return_2").html("");
                    $("#load_seat_regular_return").html("");
                    $("#lodingSeatReturn").css("display", "block");
                    $("#lodingSeatReturn2").css("display", "block");
                    setTimeout(function () {
                        $("#lodingSeatReturn").css("display", "none");
                        $("#lodingSeatReturn2").css("display", "none");
                        $("#load_seat_return_2").html(res.isi);
                        $("#load_seat_regular_return").html(res.isiR);
                    }, 500);
                } else {
                    $("#load_seat_regular_return").html("");
                    $("#load_seat_return_2").html("");
                }
            },
        });

        // $.ajax({
        //     type: "POST", // Metode pengiriman data menggunakan POST
        //     url: BASEURL + "home/getSeatRegularReturn", // File yang akan memproses data
        //     data: {
        //         idJad: idJad,
        //         idJadwal: idJadwal,
        //         dataReturn: dataReturn,
        //     }, // Data yang akan dikirim ke file pemroses
        //     success: function (response) {
        //         // Jika berhasil
        //         var res = JSON.parse(response);
        //         if (oneways == "true") {
        //             $("#load_seat_regular_return").html(res.isi);
        //         } else {
        //             $("#load_seat_regular_return").html("");
        //         }
        //     },
        // });

        stepsWizard.steps("next");
    });

    $(document).delegate(".seatcheck", "click", function () {
        let seatCount = $('input[name="seat[]"]:checked+label').length;
        let quantityAdult = $("#quantity__input1").val();
        let quantityChild = $("#quantity__input2").val();
        let totalPenumpang =
            parseInt(quantityAdult) +
            parseInt(quantityChild !== "" ? quantityChild : 0);
        let btnSkip = $("#next").attr("data-id");

        if (seatCount == totalPenumpang) {
            $('input[name="seat[]"]:not(:checked)').attr(
                "disabled",
                "disabled",
            );
            if (btnSkip == "3") {
                stepsWizard.steps("next");
                stepsWizard.steps("next");
                stepsWizard.steps("next");
            } else {
                stepsWizard.steps("next");
            }
        } else if (seatCount < totalPenumpang) {
            $('.seatcheck[name="seat[]"]:not(:checked)').removeAttr("disabled");
        }
    });

    $(document).delegate(".seatReturnCheck", "click", function () {
        let seatCount = $('input[name="seatReturn[]"]:checked+label').length;
        let quantityAdult = $("#quantity__input1").val();
        let quantityChild = $("#quantity__input2").val();
        let totalPenumpang =
            parseInt(quantityAdult) +
            parseInt(quantityChild !== "" ? quantityChild : 0);
        let btnBak = $("#back").attr("data-id");

        if (seatCount == totalPenumpang) {
            $('input[name="seatReturn[]"]:not(:checked)').attr(
                "disabled",
                "disabled",
            );
            stepsWizard.steps("next");
        } else if (seatCount < totalPenumpang) {
            $('.seatReturnCheck[name="seatReturn[]"]:not(:checked)').removeAttr(
                "disabled",
            );
        }
    });

    // Grid
    $(".grid .grid-container-item").click(function () {
        $(".grid .grid-item").removeClass("active");
        $(this).children("div.grid-item").addClass("active");
    });
});
