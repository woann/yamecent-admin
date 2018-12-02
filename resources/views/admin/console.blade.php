@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>
              </span>
                    Dashboard
                </h3>
                {{--<nav aria-label="breadcrumb">--}}
                {{--<ul class="breadcrumb">--}}
                {{--<li class="breadcrumb-item active" aria-current="page">--}}
                {{--<span></span>Overview--}}
                {{--<i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--</nav>--}}
            </div>
            <div class="row">
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body">
                            <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                            <h4 class="font-weight-normal mb-3">Weekly Sales
                                <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">$ 15,0000</h2>
                            <h6 class="card-text">Increased by 60%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body">
                            <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                            <h4 class="font-weight-normal mb-3">Weekly Orders
                                <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">45,6334</h2>
                            <h6 class="card-text">Decreased by 10%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white">
                        <div class="card-body">
                            <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                            <h4 class="font-weight-normal mb-3">Visitors Online
                                <i class="mdi mdi-diamond mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">95,5741</h2>
                            <h6 class="card-text">Increased by 5%</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="clearfix">
                                <h4 class="card-title float-left">Visit And Sales Statistics</h4>
                                <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>
                            </div>
                            <canvas id="visit-sale-chart" class="mt-4"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Traffic Sources</h4>
                            <canvas id="traffic-chart"></canvas>
                            <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Recent Tickets</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>
                                            Assignee
                                        </th>
                                        <th>
                                            Subject
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Last Update
                                        </th>
                                        <th>
                                            Tracking ID
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/faces/face1.jpg" class="mr-2" alt="image">
                                            David Grey
                                        </td>
                                        <td>
                                            Fund is not recieved
                                        </td>
                                        <td>
                                            <label class="badge badge-gradient-success">DONE</label>
                                        </td>
                                        <td>
                                            Dec 5, 2017
                                        </td>
                                        <td>
                                            WD-12345
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/faces/face2.jpg" class="mr-2" alt="image">
                                            Stella Johnson
                                        </td>
                                        <td>
                                            High loading time
                                        </td>
                                        <td>
                                            <label class="badge badge-gradient-warning">PROGRESS</label>
                                        </td>
                                        <td>
                                            Dec 12, 2017
                                        </td>
                                        <td>
                                            WD-12346
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/faces/face3.jpg" class="mr-2" alt="image">
                                            Marina Michel
                                        </td>
                                        <td>
                                            Website down for one week
                                        </td>
                                        <td>
                                            <label class="badge badge-gradient-info">ON HOLD</label>
                                        </td>
                                        <td>
                                            Dec 16, 2017
                                        </td>
                                        <td>
                                            WD-12347
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/faces/face4.jpg" class="mr-2" alt="image">
                                            John Doe
                                        </td>
                                        <td>
                                            Loosing control on server
                                        </td>
                                        <td>
                                            <label class="badge badge-gradient-danger">REJECTED</label>
                                        </td>
                                        <td>
                                            Dec 3, 2017
                                        </td>
                                        <td>
                                            WD-12348
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
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Recent Updates</h4>
                            <div class="d-flex">
                                <div class="d-flex align-items-center mr-4 text-muted font-weight-light">
                                    <i class="mdi mdi-account-outline icon-sm mr-2"></i>
                                    <span>jack Menqu</span>
                                </div>
                                <div class="d-flex align-items-center text-muted font-weight-light">
                                    <i class="mdi mdi-clock icon-sm mr-2"></i>
                                    <span>October 3rd, 2018</span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 pr-1">
                                    <img src="/assets/images/dashboard/img_1.jpg" class="mb-2 mw-100 w-100 rounded" alt="image">
                                    <img src="/assets/images/dashboard/img_4.jpg" class="mw-100 w-100 rounded" alt="image">
                                </div>
                                <div class="col-6 pl-1">
                                    <img src="/assets/images/dashboard/img_2.jpg" class="mb-2 mw-100 w-100 rounded" alt="image">
                                    <img src="/assets/images/dashboard/img_3.jpg" class="mw-100 w-100 rounded" alt="image">
                                </div>
                            </div>
                            <div class="d-flex mt-5 align-items-top">
                                <img src="/assets/images/faces/face3.jpg" class="img-sm rounded-circle mr-3" alt="image">
                                <div class="mb-0 flex-grow">
                                    <h5 class="mr-2 mb-2">School Website - Authentication Module.</h5>
                                    <p class="mb-0 font-weight-light">It is a long established fact that a reader will be distracted by the readable
                                        content of a page.</p>
                                </div>
                                <div class="ml-auto">
                                    <i class="mdi mdi-heart-outline text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Project Status</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Due Date
                                        </th>
                                        <th>
                                            Progress
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            1
                                        </td>
                                        <td>
                                            Herman Beck
                                        </td>
                                        <td>
                                            May 15, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            2
                                        </td>
                                        <td>
                                            Messsy Adam
                                        </td>
                                        <td>
                                            Jul 01, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            3
                                        </td>
                                        <td>
                                            John Richards
                                        </td>
                                        <td>
                                            Apr 12, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            4
                                        </td>
                                        <td>
                                            Peter Meggik
                                        </td>
                                        <td>
                                            May 15, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            5
                                        </td>
                                        <td>
                                            Edward
                                        </td>
                                        <td>
                                            May 03, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            5
                                        </td>
                                        <td>
                                            Ronald
                                        </td>
                                        <td>
                                            Jun 05, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2017-2018 <a href="http://www.yamecent.com/" target="_blank">Yamecent</a>. All rights reserved. </span>
            </div>
        </footer>
        <!-- partial -->
    </div>
@endsection