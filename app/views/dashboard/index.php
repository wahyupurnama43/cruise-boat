<div class="wrapper wrapper-content">
    <div class="row">

        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">Today</span>
                    <h5>Total Books</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?= $data['totalBooks'] ?></h1>
                    <div class="stat-percent font-bold text-navy"><?= $data['books'] ?> % <i class="fa fa-level-up"></i></div>
                    <small>New book</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Total Agents</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?= $data['totalUser'] ?></h1>
                    <!-- <div class="stat-percent font-bold text-info">40% <i class="fa fa-level-up"></i></div> -->
                    <small>New agents</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-warning pull-right">month</span>
                    <h5>Total Income </h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">IDR <?= number_format($data['totalPrice']['totalPrice']) ?></h1>
                    <!-- <div class="stat-percent font-bold text-warning">16% <i class="fa fa-level-up"></i></div> -->
                    <small>New Income</small>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div>

                        <h1 class="m-b-xs">Passenger Chart</h1>
                        <h3 class="font-bold no-margins">
                            Monthly Passenger Chart
                        </h3>
                        <small> Year of 2019</small>
                    </div>

                    <div>
                        <canvas id="lineChart" height="70"></canvas>
                    </div>

                    <div class="m-t-md">
                        <small class="pull-right">
                            <i class="fa fa-clock-o"> </i>
                            Update on 16.07.2015
                        </small>
                        <small>
                            <strong>*)Note:</strong> asjaksjajsk kasdnkjsnd sahbdjs
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Tabel Bookings </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group"><input type="text" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
                                    <button type="button" class="btn btn-sm btn-primary"> Go!</button> </span></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>

                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Departure</th>
                                    <th>Cruise Type </th>
                                    <th>Completed </th>
                                    <th>Task </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>C1234</td>
                                    <td>Jul 14, 2019</td>
                                    <td>Cruise 1</td>
                                    <td><span class="pie">0.2/1.561</span></td>
                                    <td>20%</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-success">Agent List </button></a>
                                        <a href="#"><button type="button" class="btn btn-xs btn-warning">Passenger List</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>C1234</td>
                                    <td>May 24, 2019</td>
                                    <td>Cruise 2</td>
                                    <td><span class="pie">0.52/1.1</span></td>
                                    <td>50%</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-success">Agent List </button></a>
                                        <a href="#"><button type="button" class="btn btn-xs btn-warning">Passenger List</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>D6789</td>
                                    <td>May 18, 2019</td>
                                    <td>Cruise 1</td>
                                    <td><span class="pie">0.8/1.3</span></td>
                                    <td>50%</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-success">Agent List </button></a>
                                        <a href="#"><button type="button" class="btn btn-xs btn-warning">Passenger List</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>D6789</td>
                                    <td>May 10, 2019</td>
                                    <td>Cruise 1</td>
                                    <td><span class="pie">0.9/1.2</span></td>
                                    <td>90%</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-success">Agent List </button></a>
                                        <a href="#"><button type="button" class="btn btn-xs btn-warning">Passenger List</button></a>
                                    </td>
                                <tr>
                                    <td>5</td>
                                    <td>D6789</td>
                                    <td>May 9, 2019</td>
                                    <td>Cruise 2</td>
                                    <td><span class="pie">1</span></td>
                                    <td>100%</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-success">Agent List </button></a>
                                        <a href="#"><button type="button" class="btn btn-xs btn-warning">Passenger List</button></a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Agent Payment Terms </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group"><input type="text" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
                                    <button type="button" class="btn btn-sm btn-primary"> Go!</button> </span></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>

                                    <th>#</th>
                                    <th>Agent Name</th>
                                    <th>Booking ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Agen 1</td>
                                    <td>C1234</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-warning" disabled>Due Date</button></a></td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-default">Print Invoice</button></a>
                                        <a href="#"><button type="button" class="btn btn-xs btn-default">See Agent Detail</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Agen 24</td>
                                    <td>C1234</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-warning" disabled>Due Date</button></a></td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-default">Print Invoice</button></a>
                                        <a href="#"><button type="button" class="btn btn-xs btn-default">See Agent Detail</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Agen 3</td>
                                    <td>C1234</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-danger" disabled>Pass due</button></a></td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-default">Print Invoice</button></a>
                                        <a href="#"><button type="button" class="btn btn-xs btn-default">See Agent Detail</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Agen 7</td>
                                    <td>C1234</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-primary" disabled>Completed</button></a></td>
                                    <td>
                                        <a href="#"><button type="button" class="btn btn-xs btn-default">See Agent Detail</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Agen 2</td>
                                    <td>C1234</td>
                                    <td><a href="#"><button type="button" class="btn btn-xs btn-primary" disabled>Completed</button></a></td>
                                    <td><a href="#">
                                            <a href="#"><button type="button" class="btn btn-xs btn-default">See Agent Detail</button></a>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div> -->

</div>