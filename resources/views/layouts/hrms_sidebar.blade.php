<aside id="sidebar_left" class="nano nano-light affix">

    <!-- Start: Sidebar Left Content -->
    <div class="sidebar-left-content nano-content">

        <!-- Sidebar Widget - Author -->
        <div class="sidebar-widget author-widget">
            <div class="media">
              <a class="media-left" href="{{url('employee/view/'.$auth->employee_no)}}">
                @if(empty($auth->photo))
                <img src="{{asset('img/placeholder.png')}}" alt="avatar" class="img-responsive">
                @else
                <img src="{{$auth->full_image}}" alt="{{$auth->full_name}}" class="img-responsive">
                @endif
              </a>
              <div class="media-body">
                <div class="media-links">
                <div class="media-author">{{$auth->full_name}}</div>
                    <a href="{{url('employee/view/'.$auth->employee_no)}}" class="sidebar-menu-toggle">{{$auth->designation->designation_name}} - </a>
                    <a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><span class="fa fa-power-off pr5"></span>Logout
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
              </div>
            </div>
        </div>

        <!-- Start: Sidebar Menu -->
        <ul class="nav sidebar-menu">
            <li class="sidebar-label pt20"></li>
            <li>
                <a href="{{url('/')}}">
                  <span class="glyphicon glyphicon-home"></span>
                  <span class="sidebar-title">Dashboard</span>
                </a>
            </li>

            <?php 
                $moduleShare = session('moduleShare');
                $userModuleShare = session('userModuleShare');
                $userMenuShare = session('userMenuShare');
            ?>
                @foreach($moduleShare as $info)
                    @if(in_array($info->id, $userModuleShare))

                    <li>
                        <?php 
                            //this calculation to open Module menu
                            $segmentUrl = \Request::segment(1);
                            $module_tag = 0;
                            $menuShare = session('menuShare');
                        ?>    
                        @foreach($menuShare as $val)
                            <?php 
                                $strAry = explode("/", $val->menu_url);
                            ?>
                            @if($strAry[0] == $segmentUrl)
                                <?php 
                                    $module_tag = $val->module_id;
                                ?>
                            @endif
                        @endforeach
                        

                        <a class="accordion-toggle @if($info->id == $module_tag) menu-open @endif" href="#">
                            <span class="{{$info->module_icon_class}}" aria-hidden="true"></span>
                            <span class="sidebar-title">{{$info->module_name}}</span>
                            <span class="caret"></span>
                        </a>
                        
                        <ul class="nav sub-nav">
                            @foreach($info->menus as $mInfo)
                                @if(in_array($mInfo->menu_url, $userMenuShare))
                                    @if($mInfo->menu_parent_id == 0)
                                    <?php 
                                        $strMneuAry = explode("/", $mInfo->menu_url);
                                    ?>
                                    <li class="@if(\Request::segment(1) == $strMneuAry[0]) active @endif">

                                        <a href="{{url("$mInfo->menu_url")}}">
                                            <span class="{{$mInfo->menu_icon_class}}"></span> {{$mInfo->menu_section_name}}
                                        </a>
                                    </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    @endif
                @endforeach
                
                <!-- <li>
                    <a class="accordion-toggle" href="#">
                        <span class="fa fa-calendar" aria-hidden="true"></span>
                        <span class="sidebar-title">Report</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="nav sub-nav">
                        <li class="">
                            <a href="{{url('SalarySheetSummery/index')}}">
                                <span class="fa fa-level-up"></span> Summery 
                            </a>
                        </li>
                        <li class="">
                            <a href="{{url('weekend/index')}}">
                                <span class="fa fa-level-up"></span> Weekends
                            </a>
                        </li>
                    </ul>
                </li> -->
                
        </ul>
        <!-- End: Sidebar Menu -->

        <!-- Start: Sidebar Collapse Button -->
        <div class="sidebar-toggle-mini">
            <a href="#">
                <span class="fa fa-sign-out"></span>
            </a>
        </div>
        <!-- End: Sidebar Collapse Button -->
    </div>
    <!-- End: Sidebar Left Content -->
</aside>