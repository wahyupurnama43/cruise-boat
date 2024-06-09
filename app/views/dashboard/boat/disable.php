<style>
     .grid {
          display: flex;
          flex-wrap: wrap;
          justify-content: space-between;
          width: 50%;
          margin: 0 auto;
     }

     .grid-item {
          width: 100%;
          height: 176px;
          display: flex;
          margin-bottom: 21px;
          cursor: pointer;
     }

     .grid-container-item {
          width: 48%;
          margin-bottom: 21px;
          cursor: pointer;
     }

     .grid-item .thumb {
          width: 86.04%;
     }

     .grid-item .heading {
          width: 13.96%;
          text-transform: uppercase;
          font-size: 11px;
          background: #b3b3b3;
          color: #fff;
          font-family: "Poppins-Medium";
          writing-mode: tb-rl;
          transform: rotate(-180deg);
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: 500;
          letter-spacing: 1px;
     }

     .grid-item.active .heading {
          background: #3377c0;
     }

     .grid-item:hover img {
          opacity: 0.6;
     }

     .actions ul {
          display: flex;
          justify-content: space-between;
          margin-top: 42px;
     }

     .actions ul.mt-7 {
          margin-top: 7px;
     }

     .actions li a {
          border: none;
          display: inline-flex;
          height: 42px;
          width: 112px;
          align-items: center;
          color: #fff;
          cursor: pointer;
          background: #3377c0;
          text-transform: uppercase;
          justify-content: center;
          letter-spacing: 1px;
     }

     .actions li a:hover {
          background: #3b87d9;
     }

     .actions li:first-child a {
          background: none;
          border: 1px solid #3377c0;
          color: #3377c0;
     }

     .actions li:first-child a:hover {
          border-color: transparent;
          color: #fff;
          background: #3b87d9;
     }

     .actions li[aria-disabled="true"]:before {
          content: "Your must fill all fields, to be able to continue";
     }

     .actions li[aria-disabled="true"] a {
          display: none;
     }

     @media (max-width: 767px) {
          body {
               background: none;
               height: auto;
               display: block;
          }

          h3 {
               font-size: 20px;
          }

          .wrapper {
               width: auto;
               padding: 0;
          }

          .wizard {
               height: auto;
               padding: 60px 20px 20px;
          }

          .form-row {
               display: block;
          }

          .form-row .form-col {
               width: 100%;
               margin-right: 0;
               margin-bottom: 25px;
          }

          .actions ul li {
               margin-bottom: 20px;
          }

          .grid {
               display: block;
          }

          .grid-item {
               width: 100%;
               display: block;
          }

          .grid-item .thumb {
               width: 100%;
               height: 70%;
          }

          .grid-item .heading {
               width: 100%;
               writing-mode: unset;
               transform: rotate(0);
               padding: 10px 0;
               font-size: 13px;
          }

          .type-ticket {
               display: flex !important;
          }

          .grid-container-item {
               width: 100%;
          }

          .plane {
               width: 100% !important;
          }

          .ov-scroll {
               height: auto !important;
          }

          .d-desktop {
               display: none !important;
          }

          .d-mobile {
               display: block !important;
          }

          .task-collapse .toggle-collapse:checked~.collapse {
               max-height: 100%;
          }

          .jscc {
               justify-content: center;
          }
     }

     .plane {
          width: 45%;
          max-width: 800px;
     }

     .planeR {
          width: 51% !important;
     }

     @media (max-width: 576px) {
          .planeR {
               width: 100% !important;
          }
     }

     .cockpit {
          height: 250px;
          position: relative;
          overflow: hidden;
          text-align: center;
          border-bottom: 5px solid #d8d8d8;
     }

     .cockpit-back {
          height: 128px;
          position: relative;
          overflow: hidden;
          text-align: center;
          border-top: 5px solid #d8d8d8;
     }

     .cockpit:before {
          content: "";
          display: block;
          position: absolute;
          top: 0;
          left: 0;
          height: 500px;
          width: 100%;
          border-radius: 50%;
          border-right: 5px solid #d8d8d8;
          border-left: 5px solid #d8d8d8;
     }

     .cockpit-back:before {
          content: "";
          display: block;
          position: absolute;
          top: 0;
          left: 0;
          height: 100px;
          width: 100%;
          border-right: 5px solid #d8d8d8;
          border-bottom: 5px solid #d8d8d8;
          border-left: 5px solid #d8d8d8;
     }

     .cockpit h1 {
          width: 60%;
          margin: 100px auto 35px auto;
     }

     .exit,
     .exit--back,
     .exit--front {
          position: relative;
          height: 50px;
     }

     .exit--back:before {
          content: "EXIT";
          font-size: 14px;
          line-height: 18px;
          padding: 0px 2px;
          font-family: "Arial Narrow", Arial, sans-serif;
          display: block;
          position: absolute;
          background: green;
          color: white;
          top: 50%;
          left: 43%;
          transform: translate(0, -50%);
     }

     .exit:before {
          content: "EXIT";
          font-size: 14px;
          line-height: 18px;
          padding: 0px 2px;
          font-family: "Arial Narrow", Arial, sans-serif;
          display: block;
          position: absolute;
          background: green;
          color: white;
          top: 50%;
          transform: translate(0, -50%);
     }

     .exit:before {
          left: 0;
     }

     .exit:after {
          right: 0;
     }


     .fuselage {
          border-right: 5px solid #d8d8d8;
          border-left: 5px solid #d8d8d8;
     }

     ol {
          list-style: none;
          padding: 0;
          margin: 0;
     }

     .seats {
          margin-left: 26px;
          display: flex;
          flex-direction: row;
          flex-wrap: wrap;
          justify-content: flex-start;
     }

     .seat {
          display: flex;
          flex: 0 0 11%;
          padding: 3px;
          position: relative;
     }

     .vip-seats .seat {
          display: flex;
          flex: 0 0 14.285714%;
          padding: 5px;
          margin: 1px;
          position: relative;
     }

     .vip-seats {
          margin-left: 10px;
          display: flex;
          flex-direction: row;
          flex-wrap: wrap;
          justify-content: flex-start;
     }

     .regular-seat .seat:nth-child(4n) {
          margin-right: 5.285714%;
     }

     .regular-seat .seat:nth-child(8n) {
          margin-right: 0%;
     }

     .vip-seat .seat:nth-child(3n) {
          margin-right: 6.285714%;
     }

     .vip-seat .seat:nth-child(6n) {
          margin-right: 0%;
     }

     .seat input[type="checkbox"] {
          position: absolute;
          opacity: 0;
     }

     .seat input[name="seat[]"]:checked+label {
          -webkit-animation-name: rubberBand;
          animation-name: rubberBand;
          animation-duration: 300ms;
          animation-fill-mode: both;
          background: #f42536 !important;
     }

     .seat input[name="seatReturn[]"]:checked+label {
          -webkit-animation-name: rubberBand;
          animation-name: rubberBand;
          animation-duration: 300ms;
          animation-fill-mode: both;
          background: #f42536 !important;
     }

     .seat input[type="checkbox"]:disabled+label {
          background: #dddddd;
          text-indent: -9999px;
          overflow: hidden;
     }

     .seat input[type="checkbox"]:disabled+label:after {
          content: "X";
          text-indent: 0;
          position: absolute;
          top: 4px;
          left: 50%;
          transform: translate(-50%, 0%);
     }

     .seat input[type="checkbox"]:disabled+label:hover {
          box-shadow: none;
          cursor: not-allowed;
     }

     .seat label {
          color: #000;
          display: block;
          position: relative;
          width: 100%;
          text-align: center;
          font-size: 16px;
          font-weight: bold;
          line-height: 1.5rem;
          padding: 5px 0;
          background: #bada55;
          border-radius: 5px;
          animation-duration: 300ms;
          animation-fill-mode: both;
     }

     .seat label:before {
          content: "";
          position: absolute;
          width: 75%;
          height: 75%;
          top: 1px;
          left: 50%;
          transform: translate(-50%, 0%);
          background: rgba(255, 255, 255, 0.4);
          border-radius: 3px;
     }

     .seat label:hover {
          cursor: pointer;
          box-shadow: 0 0 0px 2px #5c6aff;
     }

     @-webkit-keyframes rubberBand {
          0% {
               -webkit-transform: scale3d(1, 1, 1);
               transform: scale3d(1, 1, 1);
          }

          30% {
               -webkit-transform: scale3d(1.25, 0.75, 1);
               transform: scale3d(1.25, 0.75, 1);
          }

          40% {
               -webkit-transform: scale3d(0.75, 1.25, 1);
               transform: scale3d(0.75, 1.25, 1);
          }

          50% {
               -webkit-transform: scale3d(1.15, 0.85, 1);
               transform: scale3d(1.15, 0.85, 1);
          }

          65% {
               -webkit-transform: scale3d(0.95, 1.05, 1);
               transform: scale3d(0.95, 1.05, 1);
          }

          75% {
               -webkit-transform: scale3d(1.05, 0.95, 1);
               transform: scale3d(1.05, 0.95, 1);
          }

          100% {
               -webkit-transform: scale3d(1, 1, 1);
               transform: scale3d(1, 1, 1);
          }
     }

     @keyframes rubberBand {
          0% {
               -webkit-transform: scale3d(1, 1, 1);
               transform: scale3d(1, 1, 1);
          }

          30% {
               -webkit-transform: scale3d(1.25, 0.75, 1);
               transform: scale3d(1.25, 0.75, 1);
          }

          40% {
               -webkit-transform: scale3d(0.75, 1.25, 1);
               transform: scale3d(0.75, 1.25, 1);
          }

          50% {
               -webkit-transform: scale3d(1.15, 0.85, 1);
               transform: scale3d(1.15, 0.85, 1);
          }

          65% {
               -webkit-transform: scale3d(0.95, 1.05, 1);
               transform: scale3d(0.95, 1.05, 1);
          }

          75% {
               -webkit-transform: scale3d(1.05, 0.95, 1);
               transform: scale3d(1.05, 0.95, 1);
          }

          100% {
               -webkit-transform: scale3d(1, 1, 1);
               transform: scale3d(1, 1, 1);
          }
     }

     .rubberBand {
          -webkit-animation-name: rubberBand;
          animation-name: rubberBand;
     }

     .vip-seat .seats {
          justify-content: center;
     }

     .px-4 {
          padding: 0 20px;
     }

     .switch {
          margin: 10px;
          position: relative;
          display: inline-block;
          width: 60px;
          height: 26px;
     }

     .switch input {
          opacity: 0;
          width: 0;
          height: 0;
     }
</style>

<div class="row wrapper border-bottom white-bg page-heading">
     <div class="col-lg-8">
          <h2>Boat</h2>
          <ol class="breadcrumb">
               <li>
                    <a href="<?= BASEURL ?>/dashboard">Home</a>
               </li>
               <li class="active">
                    Table Boat
               </li>
          </ol>
     </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
     <div class="row">
          <div class="col-lg-12">
               <div class="ibox float-e-margins">
                    <div class="ibox-title">
                         <h5> Table Boat </h5>
                    </div>
                    <div class="ibox-content">
                         <div class="grid ov-scroll">
                              <div class="plane">
                                   <div class="cockpit">
                                        <h1>VIP Seat</h1>
                                   </div>
                                   <div class="exit exit--front fuselage">
                                   </div>

                                   <ol class="vip-seat cabin fuselage">
                                        <li class="row row--1">
                                             <ol class="vip-seats" type="A" id="load_seat">

                                                  <?php
                                                  $nomor = 0;
                                                  $char  = 0;
                                                  $alpha = range('A', 'Z');
                                                  if ($data['getBoot']['id'] == 12) {
                                                       $seatVIPNew = $data['getBoot']['boatSeatVIP'] + 3;
                                                  } else {
                                                       $seatVIPNew = $data['getBoot']['boatSeatVIP'];
                                                  }
                                                  for ($i = 1; $i <= $seatVIPNew; $i++) {
                                                       ++$nomor;
                                                       if ($nomor == 7) {
                                                            $nomor = 1;
                                                       }
                                                       if ($nomor % 6 == 0) {
                                                            $char++;
                                                       } else {
                                                            $num = $alpha[$char];
                                                       }

                                                       $seat = $num . $nomor;

                                                       // $kursi = $this->model('M_Home')->getSeatBooking($seat, $idJadwal, $sqldate);

                                                       $kursiR = Controller::model('M_Boat')->getDisableSeat($seat, $data['getBoot']['id']);

                                                       if ($kursiR['seat'] == $seat) {
                                                            $disabled = 'checked';
                                                       } else {
                                                            $disabled = '';
                                                       }

                                                  ?>
                                                       <li class="seat">
                                                            <input type="checkbox" id="<?= $seat ?>" value="<?= $seat ?>" name="seat[]" class="seatcheck" <?= $disabled ?> />
                                                            <label for="<?= $seat ?>" class="seatChoose"><?= $seat ?></label>
                                                       </li>
                                                  <?php } ?>
                                             </ol>
                                        </li>
                                   </ol>

                                   <div class="exit exit--back fuselage">
                                   </div>
                                   <div class="cockpit-back">
                                   </div>
                              </div>
                              <div class="plane planeR">
                                   <div class="cockpit">
                                        <h1>Regular Seat</h1>
                                   </div>
                                   <div class="exit exit--front fuselage">
                                   </div>
                                   <ol class="regular-seat cabin fuselage">
                                        <li class="row row--1">
                                             <ol class="seats" type="A" id="load_seat_regular">

                                                  <?php
                                                  $nomor = 0;
                                                  for ($i = 1; $i <= $data['getBoot']['boatSeat']; $i++) {
                                                       ++$nomor;
                                                       if ($nomor == 9) {
                                                            $nomor = 1;
                                                       }
                                                       if ($i % 8 == 0) {
                                                            $char++;
                                                       } else {
                                                            $num = $alpha[$char];
                                                       }

                                                       $seat = $num . $nomor;

                                                       $kursiR = Controller::model('M_Boat')->getDisableSeat($seat, $data['getBoot']['id']);

                                                       if ($kursiR['seat'] == $seat) {
                                                            $disabled = 'checked';
                                                       } else {
                                                            $disabled = '';
                                                       }


                                                  ?>
                                                       <li class="seat">
                                                            <input type="checkbox" id="<?= $seat ?>" value="<?= $seat ?>" name="seat[]" class="seatcheck" <?= $disabled ?> />
                                                            <label for="<?= $seat ?>" class="seatChoose"><?= $seat ?></label>
                                                       </li>
                                                  <?php } ?>
                                             </ol>
                                        </li>
                                   </ol>
                                   <div class="exit exit--back fuselage">
                                   </div>
                                   <div class="cockpit-back">
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>


<script>
     $('.seatcheck').click(function() {
          let seat = $(this).val();
          $.ajax({
               type: "POST", // Metode pengiriman data menggunakan POST
               url: '<?= BASEURL ?>' + "dashboard/disable_seat", // File yang akan memproses data
               data: {
                    boat: <?= $data['getBoot']['id'] ?>,
                    seat: seat
               }, // Data yang akan dikirim ke file pemroses
               success: function(response) {
                    // Jika berhasil
                    var res = JSON.parse(response);
               },
          });
     });
</script>