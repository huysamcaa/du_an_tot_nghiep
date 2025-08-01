

<!-- resources/views/home.blade.php -->
@extends('client.layouts.app')

@section('title', 'Trang chủ')

@section('content')
     <body>
        <!-- BEGIN: PreLoder Section -->
        <section class="preloader" id="preloader">
            <div class="spinner-eff spinner-eff-1">
                <div class="bar bar-top"></div>
                <div class="bar bar-right"></div>
                <div class="bar bar-bottom"></div>
                <div class="bar bar-left"></div>
            </div>
        </section>
        <!-- END: PreLoder Section -->

        <!-- BEGIN: Header 01 Section -->

        <!-- END: Header 01 Section -->

        <!-- BEGIN: Search Popup Section -->
        <section class="popup_search_sec">
            <div class="popup_search_overlay"></div>
            <div class="pop_search_background">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="popup_logo">
                                <a href="index.html"><img src="{{ asset('assets/Client/images/logo2.png') }}" alt="Ulina"></a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <a href="javascript:void(0);" id="search_Closer" class="search_Closer"></a>
                        </div>
                    </div>
                </div>
                <div class="middle_search">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <div class="popup_search_form">
                                    <form method="get" action="#">
                                        <input type="search" name="s" id="s" placeholder="Type Words and Hit Enter">
                                        <button type="submit"><i class="fa-solid fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Search Popup Section -->

        <!-- BEGIN: Slider Section -->
        <section class="sliderSection01">
            <div class="rev_slider_wrapper">
                <div id="rev_slider_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
                    <ul>
                        <li data-index="rs-3046" data-transition="random-premium" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1000"  data-thumb=""  data-rotate="0"  data-saveperformance="off"  data-title="" data-param1="01" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                            <img src="{{ asset('assets/Client/images/slider/1.png') }}" alt="Ulina Slider" class="rev-slidebg" />
                            <div class="tp-caption tp-resizeme layer01 shapeImage"
                                 data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-x="['right','right','right','right']"
                                 data-hoffset="['192','-100','0','0']"

                                 data-y="['bottom','bottom','bottom','bottom']"
                                 data-voffset="['71','71','-300','0']"
                                 data-width="['auto','auto','60%','auto']"
                                 data-height="auto"
                                 data-visibility="['on','on','on','off']"
                                 data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/s1.png') }}" alt="Slider Shape"/></div>
                            <div class="tp-caption tp-resizeme rs-parallaxlevel-2 layer03 personImage"
                                 data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-x="['right','right','right','right']"
                                 data-hoffset="['300','0','0','0']"

                                 data-y="['bottom','bottom','bottom','bottom']"
                                 data-voffset="['0','0','-300','0']"
                                 data-width="['auto','auto','60%','80%']"
                                 data-height="auto"
                                 data-visibility="['on','on','on','off']"
                                 data-textAlign="['left','left','left','right']"
                                 data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/person_1.png') }}" alt="Slider Shape"/></div>
                            <div class="tp-caption jost theSubTitle"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['0','0','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['-38','-38','-38','-58']"

                                 data-fontsize="['21','21','21','21']"
                                 data-fontweight="['500','500','500','500']"
                                 data-lineheight="['21','21','21','21']"
                                 data-width="['auto','auto','auto','100%']"
                                 data-height="none"
                                 data-whitespace="nowrap"
                                 data-color="['#7b9496','#7b9496','#7b9496','#7b9496']"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1100,"speed":400,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['left','left','left','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','10','10','0']"

                                 >Giảm Giá Hôm Nay</div>
                            <div class="tp-caption jost textLayer theTitles"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['-5','-5','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['68','68','68','28']"

                                 data-fontsize="['72','72','52','52']"
                                 data-fontweight="['400','400','400','400']"
                                 data-lineheight="['84','84','64','64']"
                                 data-width="['550','550','450','100%']"
                                 data-height="none"
                                 data-whitespace="normal"
                                 data-color="['#52586d','#52586d','#52586d','#52586d']"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['left','left','left','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','0','0','0']"

                                 >Quần Áo Nam Thời Thượng</div>
                            <div class="tp-caption ws_nowrap textLayer theBTNS"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['0','0','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['231','231','211','171']"

                                 data-fontsize="['16','16','16','16']"
                                 data-fontweight="500"
                                 data-lineheight="['55','55','55','55']"
                                 data-width="['auto','auto','auto','100%']"
                                 data-height="auto"
                                 data-whitesapce="normal"
                                 data-color="#FFFFFF"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['center','center','center','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','0','0','0']"

                                 ><a class="ulinaBTN ulinaSliderBTN" href="collections.html"><span>Explore Now</span></a>
                            </div>
                        </li>
                        <li data-index="rs-3047" data-transition="random-premium" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1000"  data-thumb=""  data-rotate="0"  data-saveperformance="off"  data-title="" data-param1="01" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                            <img src="{{ asset('assets/Client/images/slider/1.png') }}" alt="Ulina Slider" class="rev-slidebg" />
                            <div class="tp-caption tp-resizeme layer01 shapeImage"
                                 data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-x="['right','right','right','right']"
                                 data-hoffset="['192','-100','0','0']"

                                 data-y="['bottom','bottom','bottom','bottom']"
                                 data-voffset="['71','71','-300','0']"
                                 data-width="['auto','auto','60%','auto']"
                                 data-height="auto"
                                 data-visibility="['on','on','on','off']"
                                 data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/s1.png') }}" alt="Slider Shape"/></div>
                            <div class="tp-caption tp-resizeme rs-parallaxlevel-2 layer03 personImage"
                                 data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-x="['right','right','right','right']"
                                 data-hoffset="['300','0','0','0']"

                                 data-y="['bottom','bottom','bottom','bottom']"
                                 data-voffset="['0','0','-300','0']"
                                 data-width="['auto','auto','60%','80%']"
                                 data-height="auto"
                                 data-visibility="['on','on','on','off']"
                                 data-textAlign="['left','left','left','right']"
                                 data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/person_2.png') }}" alt="Slider Shape"/></div>
                            <div class="tp-caption jost theSubTitle"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['0','0','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['-38','-38','-38','-58']"

                                 data-fontsize="['21','21','21','21']"
                                 data-fontweight="['500','500','500','500']"
                                 data-lineheight="['21','21','21','21']"
                                 data-width="['auto','auto','auto','100%']"
                                 data-height="none"
                                 data-whitespace="nowrap"
                                 data-color="['#7b9496','#7b9496','#7b9496','#7b9496']"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1100,"speed":400,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['left','left','left','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','10','10','0']"

                                 >Giảm Giá Hôm Nay</div>
                            <div class="tp-caption jost textLayer theTitles"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['-5','-5','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['68','68','68','28']"

                                 data-fontsize="['72','72','52','52']"
                                 data-fontweight="['400','400','400','400']"
                                 data-lineheight="['84','84','64','64']"
                                 data-width="['550','550','450','100%']"
                                 data-height="none"
                                 data-whitespace="normal"
                                 data-color="['#52586d','#52586d','#52586d','#52586d']"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['left','left','left','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','0','0','0']"

                                 >Quần Áo Nam Thời Thượng</div>
                            <div class="tp-caption ws_nowrap textLayer theBTNS"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['0','0','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['231','231','211','171']"

                                 data-fontsize="['16','16','16','16']"
                                 data-fontweight="500"
                                 data-lineheight="['55','55','55','55']"
                                 data-width="['auto','auto','auto','100%']"
                                 data-height="auto"
                                 data-whitesapce="normal"
                                 data-color="#FFFFFF"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['center','center','center','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','0','0','0']"

                                 ><a class="ulinaBTN ulinaSliderBTN" href="collections.html"><span>Explore Now</span></a>
                            </div>
                        </li>
                        <li data-index="rs-3048" data-transition="random-premium" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1000"  data-thumb=""  data-rotate="0"  data-saveperformance="off"  data-title="" data-param1="01" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                            <img src="{{ asset('assets/Client/images/slider/1.png') }}" alt="Ulina Slider" class="rev-slidebg" />
                            <div class="tp-caption tp-resizeme layer01 shapeImage"
                                 data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-x="['right','right','right','right']"
                                 data-hoffset="['192','-100','0','0']"

                                 data-y="['bottom','bottom','bottom','bottom']"
                                 data-voffset="['71','71','-300','0']"
                                 data-width="['auto','auto','60%','auto']"
                                 data-height="auto"
                                 data-visibility="['on','on','on','off']"
                                 data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/s1.png') }}" alt="Slider Shape"/></div>
                            <div class="tp-caption tp-resizeme rs-parallaxlevel-2 layer03 personImage"
                                 data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-x="['right','right','right','right']"
                                 data-hoffset="['300','0','0','0']"

                                 data-y="['bottom','bottom','bottom','bottom']"
                                 data-voffset="['0','0','-300','0']"
                                 data-width="['auto','auto','60%','80%']"
                                 data-height="auto"
                                 data-visibility="['on','on','on','off']"
                                 data-textAlign="['left','left','left','right']"
                                 data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/person_3.png') }}" alt="Slider Shape"/></div>
                            <div class="tp-caption jost theSubTitle"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['0','0','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['-38','-38','-38','-58']"

                                 data-fontsize="['21','21','21','21']"
                                 data-fontweight="['500','500','500','500']"
                                 data-lineheight="['21','21','21','21']"
                                 data-width="['auto','auto','auto','100%']"
                                 data-height="none"
                                 data-whitespace="nowrap"
                                 data-color="['#7b9496','#7b9496','#7b9496','#7b9496']"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1100,"speed":400,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['left','left','left','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','10','10','0']"

                                 >Giảm Giá Hôm Nay</div>
                            <div class="tp-caption jost textLayer theTitles"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['-5','-5','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['68','68','68','28']"

                                 data-fontsize="['72','72','52','52']"
                                 data-fontweight="['400','400','400','400']"
                                 data-lineheight="['84','84','64','64']"
                                 data-width="['550','550','450','100%']"
                                 data-height="none"
                                 data-whitespace="normal"
                                 data-color="['#52586d','#52586d','#52586d','#52586d']"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['left','left','left','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','0','0','0']"

                                 >Quần Áo Nam Thời Thượng</div>
                            <div class="tp-caption ws_nowrap textLayer theBTNS"
                                 data-x="['left','left','left','center']"
                                 data-hoffset="['0','0','0','0']"

                                 data-y="['middle','middle','middle','middle']"
                                 data-voffset="['231','231','211','171']"

                                 data-fontsize="['16','16','16','16']"
                                 data-fontweight="500"
                                 data-lineheight="['55','55','55','55']"
                                 data-width="['auto','auto','auto','100%']"
                                 data-height="auto"
                                 data-whitesapce="normal"
                                 data-color="#FFFFFF"

                                 data-type="text"
                                 data-responsive_offset="off"

                                 data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['center','center','center','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','0','0','0']"

                                 ><a class="ulinaBTN ulinaSliderBTN" href="collections.html"><span>Explore Now</span></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- END: Slider Section -->

        <!-- BEGIN: Feature Section -->
        <section class="featureSection">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="iconBox01">
                            <i class="ulina-fast-delivery"></i>
                            <h3>Miễn Phí Ship</h3>
                            <p>
                               Chúng tôi giao hàng miễn phí trên toàn quốc cho mọi đơn hàng.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="iconBox01">
                            <i class="ulina-credit-card tm5"></i>
                            <h3>Thanh toán an toàn</h3>
                            <p>
                                   Hệ thống thanh toán bảo mật giúp bạn yên tâm khi mua sắm.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="iconBox01">
                            <i class="ulina-refund tm1"></i>
                            <h3>Đổi trả dễ dàng</h3>
                            <p>
                                Nếu không ưng ý, bạn có thể đổi trả nhanh chóng và đơn giản.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="iconBox01">
                            <i class="ulina-hours-support t1"></i>
                            <h3>Hỗ trợ 24/7</h3>
                            <p>
                                Đội ngũ CSKH luôn sẵn sàng hỗ trợ bạn mọi lúc mọi nơi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Feature Section -->

        <!-- BEGIN: Latest Arrival Section -->
        <section class="latestArrivalSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="secTitle">Sản Phẩm Của Chúng Tôi</h2>
                        <p class="secDesc">Chúng tôi cam kết chất lượng cao, giá cạnh tranh và dịch vụ giao hàng nhanh chóng.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="productCarousel owl-carousel">
                            @foreach ($products as $product)
                            <div class="productItem01">
    <div class="pi01Thumb" style="height: 400px; overflow: hidden;">
        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" style="width: 100%; height: auto; object-fit: cover;" />
        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" style="width: 100%; height: auto; object-fit: cover;" />

        
        <div class="pi01Actions" data-product-id="{{ $product->id }}">

            <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
            <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
            <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
            <a href="{{ route('product.detail', $product->id) }}"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>

        </div>

        @if ($product->sale_price && $product->price > $product->sale_price)
            <div class="productLabels clearfix">
                <span class="plDis">
                    - {{ number_format($product->price - $product->sale_price, 0, ',', '.') }}đ
                </span>
                <span class="plSale">SALE</span>
            </div>
        @endif
    </div>

    <div class="pi01Details">
        <!-- <div class="productRatings">
            <div class="productRatingWrap">
                <div class="star-rating"><span></span></div>
            </div>
            <div class="ratingCounts">10 đánh giá</div>
        </div> -->

        <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $product->name }}">
            {{ $product->name }}
        </h3>

        <div class="pi01Price">
            <ins>{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</ins>
            @if ($product->sale_price && $product->price > $product->sale_price)
                <del>{{ number_format($product->price, 0, ',', '.') }}đ</del>
            @endif
        </div>

        <!-- <div class="pi01Variations">
            <div class="pi01VColor">
                <div class="pi01VCItem">
                    <input checked type="radio" name="color_{{ $product->id }}" value="Blue" id="color_{{ $product->id }}_blue" />
                    <label for="color_{{ $product->id }}_blue"></label>
                </div>
                <div class="pi01VCItem yellows">
                    <input type="radio" name="color_{{ $product->id }}" value="Yellow" id="color_{{ $product->id }}_yellow" />
                    <label for="color_{{ $product->id }}_yellow"></label>
                </div>
                <div class="pi01VCItem reds">
                    <input type="radio" name="color_{{ $product->id }}" value="Red" id="color_{{ $product->id }}_red" />
                    <label for="color_{{ $product->id }}_red"></label>
                </div>
            </div>

            <div class="pi01VSize">
                <div class="pi01VSItem">
                    <input type="radio" name="size_{{ $product->id }}" value="S" id="size_{{ $product->id }}_s" />
                    <label for="size_{{ $product->id }}_s">S</label>
                </div>
                <div class="pi01VSItem">
                    <input type="radio" name="size_{{ $product->id }}" value="M" id="size_{{ $product->id }}_m" />
                    <label for="size_{{ $product->id }}_m">M</label>
                </div>
                <div class="pi01VSItem">
                    <input type="radio" name="size_{{ $product->id }}" value="XL" id="size_{{ $product->id }}_xl" />
                    <label for="size_{{ $product->id }}_xl">XL</label>
                </div>
            </div>
        </div> -->
    </div>
</div>


@endforeach
                            <!-- <div class="productItem01 pi01NoRating">
                                <div class="pi01Thumb">
                                    <img src="{{ asset('assets/Client/images/products/2.jpg') }}" alt="Ulina Product"/>
                                    <img src="{{ asset('assets/Client/images/products/2.1.jpg') }}" alt="Ulina Product"/>
                                    <div class="pi01Actions">
                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                    </div>
                                    <div class="productLabels clearfix">
                                        <span class="plHot">Hot</span>
                                    </div>
                                </div>
                                <div class="pi01Details">
                                    <h3><a href="shop_details2.html">Ulina black clean t-shirt</a></h3>
                                    <div class="pi01Price">
                                        <ins>$14</ins>
                                        <del>$30</del>
                                    </div>
                                    <div class="pi01Variations">
                                        <div class="pi01VColor">
                                            <div class="pi01VCItem">
                                                <input checked type="radio" name="color2" value="Blue" id="color2_blue"/>
                                                <label for="color2_blue"></label>
                                            </div>
                                            <div class="pi01VCItem yellows">
                                                <input type="radio" name="color2" value="Yellow" id="color2_yellow"/>
                                                <label for="color2_yellow"></label>
                                            </div>
                                            <div class="pi01VCItem reds">
                                                <input type="radio" name="color2" value="Red" id="color2_red"/>
                                                <label for="color2_red"></label>
                                            </div>
                                        </div>
                                        <div class="pi01VSize">
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size2" value="Blue" id="size2_s"/>
                                                <label for="size2_s">S</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size2" value="Yellow" id="size2_m"/>
                                                <label for="size2_m">M</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size2" value="Red" id="size2_xl"/>
                                                <label for="size2_xl">XL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="productItem01 pi01NoRating">
                                <div class="pi01Thumb">
                                    <img src="{{ asset('assets/Client/images/products/3.jpg') }}" alt="Ulina Product"/>
                                    <img src="{{ asset('assets/Client/images/products/3.1.jpg') }}" alt="Ulina Product"/>
                                    <div class="pi01Actions">
                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                    </div>
                                    <div class="productLabels clearfix">
                                        <span class="plNew float-end">New</span>
                                    </div>
                                </div>
                                <div class="pi01Details">
                                    <h3><a href="shop_details1.html">Apple white jacket</a></h3>
                                    <div class="pi01Price">
                                        <ins>$39</ins>
                                        <del>$57</del>
                                    </div>
                                    <div class="pi01Variations">
                                        <div class="pi01VColor">
                                            <div class="pi01VCItem">
                                                <input checked type="radio" name="color3" value="Blue" id="color3_blue"/>
                                                <label for="color3_blue"></label>
                                            </div>
                                            <div class="pi01VCItem yellows">
                                                <input type="radio" name="color3" value="Yellow" id="color3_yellow"/>
                                                <label for="color3_yellow"></label>
                                            </div>
                                            <div class="pi01VCItem reds">
                                                <input type="radio" name="color3" value="Red" id="color3_red"/>
                                                <label for="color3_red"></label>
                                            </div>
                                        </div>
                                        <div class="pi01VSize">
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size3" value="Blue" id="size3_s"/>
                                                <label for="size3_s">S</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size3" value="Yellow" id="size3_m"/>
                                                <label for="size3_m">M</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size3" value="Red" id="size3_xl"/>
                                                <label for="size3_xl">XL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="productItem01 pi01NoRating">
                                <div class="pi01Thumb">
                                    <img src="{{ asset('assets/Client/images/products/4.jpg') }}" alt="Ulina Product"/>
                                    <img src="{{ asset('assets/Client/images/products/4.1.jpg') }}" alt="Ulina Product"/>
                                    <div class="pi01Actions">
                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                    </div>
                                </div>
                                <div class="pi01Details">
                                    <h3><a href="shop_details2.html">One color cotton t-shirt</a></h3>
                                    <div class="pi01Price">
                                        <ins>$29</ins>
                                    </div>
                                    <div class="pi01Variations">
                                        <div class="pi01VColor">
                                            <div class="pi01VCItem">
                                                <input checked type="radio" name="color4" value="Blue" id="color4_blue"/>
                                                <label for="color4_blue"></label>
                                            </div>
                                            <div class="pi01VCItem yellows">
                                                <input type="radio" name="color1" value="Yellow" id="color4_yellow"/>
                                                <label for="color4_yellow"></label>
                                            </div>
                                            <div class="pi01VCItem reds">
                                                <input type="radio" name="color4" value="Red" id="color4_red"/>
                                                <label for="color4_red"></label>
                                            </div>
                                        </div>
                                        <div class="pi01VSize">
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size4" value="Blue" id="size4_s"/>
                                                <label for="size4_s">S</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size4" value="Yellow" id="size4_m"/>
                                                <label for="size4_m">M</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size4" value="Red" id="size4_xl"/>
                                                <label for="size4_xl">XL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="productItem01">
                                <div class="pi01Thumb">
                                    <img src="{{ asset('assets/Client/images/products/5.jpg') }}" alt="Ulina Product"/>
                                    <img src="{{ asset('assets/Client/images/products/5.1.jpg') }}" alt="Ulina Product"/>
                                    <div class="pi01Actions">
                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                    </div>
                                    <div class="productLabels clearfix">
                                        <span class="plDis">- $49</span>
                                        <span class="plSale">Sale</span>
                                    </div>
                                </div>
                                <div class="pi01Details">
                                    <div class="productRatings">
                                        <div class="productRatingWrap">
                                            <div class="star-rating"><span></span></div>
                                        </div>
                                        <div class="ratingCounts">10 Reviews</div>
                                    </div>
                                    <h3><a href="shop_details1.html">Stylish white leather bag</a></h3>
                                    <div class="pi01Price">
                                        <ins>$29</ins>
                                        <del>$56</del>
                                    </div>
                                    <div class="pi01Variations">
                                        <div class="pi01VColor">
                                            <div class="pi01VCItem">
                                                <input checked type="radio" name="color5" value="Blue" id="color5_blue"/>
                                                <label for="color5_blue"></label>
                                            </div>
                                            <div class="pi01VCItem yellows">
                                                <input type="radio" name="color5" value="Yellow" id="color5_yellow"/>
                                                <label for="color5_yellow"></label>
                                            </div>
                                            <div class="pi01VCItem reds">
                                                <input type="radio" name="color5" value="Red" id="color5_red"/>
                                                <label for="color5_red"></label>
                                            </div>
                                        </div>
                                        <div class="pi01VSize">
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size5" value="Blue" id="size5_s"/>
                                                <label for="size5_s">S</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size5" value="Yellow" id="size5_m"/>
                                                <label for="size5_m">M</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size5" value="Red" id="size5_xl"/>
                                                <label for="size5_xl">XL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="productItem01">
                                <div class="pi01Thumb">
                                    <img src="{{ asset('assets/Client/images/products/6.jpg') }}" alt="Ulina Product"/>
                                    <img src="{{ asset('assets/Client/images/products/6.1.jpg') }}" alt="Ulina Product"/>
                                    <div class="pi01Actions">
                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                    </div>
                                    <div class="productLabels clearfix">
                                        <span class="plNew float-end">New</span>
                                    </div>
                                </div>
                                <div class="pi01Details">
                                    <div class="productRatings">
                                        <div class="productRatingWrap">
                                            <div class="star-rating"><span></span></div>
                                        </div>
                                        <div class="ratingCounts">13 Reviews</div>
                                    </div>
                                    <h3><a href="shop_details2.html">Luxury maroon sweater</a></h3>
                                    <div class="pi01Price">
                                        <ins>$49</ins>
                                        <del>$60</del>
                                    </div>
                                    <div class="pi01Variations">
                                        <div class="pi01VColor">
                                            <div class="pi01VCItem">
                                                <input checked type="radio" name="color6" value="Blue" id="color6_blue"/>
                                                <label for="color6_blue"></label>
                                            </div>
                                            <div class="pi01VCItem yellows">
                                                <input type="radio" name="color6" value="Yellow" id="color6_yellow"/>
                                                <label for="color6_yellow"></label>
                                            </div>
                                            <div class="pi01VCItem reds">
                                                <input type="radio" name="color6" value="Red" id="color6_red"/>
                                                <label for="color6_red"></label>
                                            </div>
                                        </div>
                                        <div class="pi01VSize">
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size6" value="Blue" id="size6_s"/>
                                                <label for="size6_s">S</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size6" value="Yellow" id="size6_m"/>
                                                <label for="size6_m">M</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size6" value="Red" id="size6_xl"/>
                                                <label for="size6_xl">XL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Latest Arrival Section -->

        <!-- BEGIN: Lookbook Section -->
        <!-- <section class="lookbookSection">
            <div class="container">
                <div class="row masonryGrid" id="masonryGrid">
                    <div class="col-md-6 col-xl-4 shafItem">
                        <div class="lookBook01 lb01M0 overLayAnim01">
                            <div class="lbContent">
                                <h3>Get 40% Off</h3>
                                <h2>Man’s Latest Collection</h2>
                                <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop Now</a>
                            </div>
                            <img src="{{ asset('assets/Client/images/home1/1.png') }}" alt="Mans Latest Collection"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4 shafItem">
                        <div class="lookBook01 lb01M1 overLayAnim01">
                            <div class="lbContent">
                                <h3>Couple Fashion</h3>
                                <h2>Best Collection for Stylish Couple</h2>
                                <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Explore Now</a>
                            </div>
                            <img src="{{ asset('assets/Client/images/home1/2.png') }}" alt="Mans Latest Collection"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4 shafItem">
                        <div class="lookBook01 lb01M2 overLayAnim01">
                            <div class="lbContent">
                                <h3>Be Stylish</h3>
                                <h2>Girl’s Latest Fashion</h2>
                                <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop Now</a>
                            </div>
                            <img src="{{ asset('assets/Client/images/home1/3.png') }}" alt="Mans Latest Collection"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4 shafItem">
                        <div class="lookBook01 lb01M3 overLayAnim01">
                            <img src="{{ asset('assets/Client/images/home1/4.png') }}" alt="Mans Latest Collection"/>
                            <div class="lbContent">
                                <h3>New Arrival</h3>
                                <h2>Exclusive Shoes Collection</h2>
                                <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Explore Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4 shafItem">
                        <div class="lookBook01 lb01M4 overLayAnim01">
                            <div class="lbContent">
                                <h3>New in 2022</h3>
                                <h2>Discover New Bag Collection</h2>
                                <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Explore Now</a>
                            </div>
                            <img src="{{ asset('assets/Client/images/home1/6.png') }}" alt="Mans Latest Collection"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4 shafItem">
                        <div class="lookBook01 lb01M5 overLayAnim01">
                            <div class="lbContent">
                                <h3>Get 40% Off</h3>
                                <h2>Ulina Trendy Sunglass</h2>
                                <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop Now</a>
                            </div>
                            <img src="{{ asset('assets/Client/images/home1/5.png') }}" alt="Mans Latest Collection"/>
                        </div>
                    </div>
                    <div class="col-lg-1 col-sm-1 shafSizer"></div>
                </div>
            </div>
        </section> -->
        <!-- END: Lookbook Section -->

        <!-- BEGIN: Deal Product Section -->
        <!-- <section class="dealProductSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="dealProductContent">
                            <h5>Sản Phẩm</h5>
                            <h2>Sản Phẩm Tạo Nên Cá Tính</h2>
                            <p>FreshFit – Áo Nam Thời Thượng FreshFit chuyên cung cấp áo nam đa phong cách: từ áo thun đơn giản, áo khoác cá tính đến blazer lịch lãm. Thiết kế trẻ trung, chất liệu thoáng mát, phù hợp với mọi dịp – đi làm, đi chơi hay tập gym. Chúng tôi cam kết chất lượng cao, giá cạnh tranh và dịch vụ giao hàng nhanh chóng.</p>
                            <div class="dpcPriceWrap">
                                <div class="pi01Price">
                                    <ins>300.000 đ</ins>
                                    <del>1000.000 đ</del>
                                </div>
                                <a href="{{ route('client.categories.index') }}" class="ulinaBTN"><span>Mua Ngay</span></a>
                            </div>
                            <div class="countDownWrap">
                                <h6></h6>
                                <div class="ulinaCountDown" data-day="26" data-month="07" data-year="2022"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dealProductImage">
                            <img src="{{ asset('assets/Client/images/home1/7.png') }}" alt="Ulima Fashionable Jeans"/>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- END: Deal Product Section -->


        <!-- BEGIN: Popular Products Section -->
         {{-- <section class="popularProductsSection">

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="secTitle">Popular Products</h2>
                        <p class="secDesc">Showing our latest arrival on this summer</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="productTabs">
                            <ul class="nav productTabsNav absolutes" id="productTab" role="tablist">
                                <li role="presentation">
                                    <button class="active" id="men-tab" data-bs-toggle="tab" data-bs-target="#men-tab-pane" type="button" role="tab" aria-controls="men-tab-pane" aria-selected="true">Men</button>
                                </li>
                                <li role="presentation">
                                    <button id="women-tab" data-bs-toggle="tab" data-bs-target="#women-tab-pane" type="button" role="tab" aria-controls="women-tab-pane" aria-selected="false">Women</button>
                                </li>
                                <li role="presentation">
                                    <button id="kids-tab" data-bs-toggle="tab" data-bs-target="#kids-tab-pane" type="button" role="tab" aria-controls="kids-tab-pane" aria-selected="false">Kids</button>
                                </li>
                                <li role="presentation">
                                    <button id="accessories-tab" data-bs-toggle="tab" data-bs-target="#accessories-tab-pane" type="button" role="tab" aria-controls="accessories-tab-pane" aria-selected="false">Accessories</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="productTabContent">
                                <div class="tab-pane fade show active" id="men-tab-pane" role="tabpanel" aria-labelledby="men-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/5.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/5.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plDis">- $29</span>
                                                        <span class="plSale">Sale</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">10 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Stylish white leather bag</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$29</ins>
                                                        <del>$56</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_1_1" value="Blue" id="color_1_1_1_blue"/>
                                                                <label for="color_1_1_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_1_1" value="Yellow" id="color_1_1_2_blue"/>
                                                                <label for="color_1_1_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_1_1" value="Red" id="color_1_1_3_blue"/>
                                                                <label for="color_1_1_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_1" value="Blue" id="size1_1_1_1"/>
                                                                <label for="size1_1_1_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_1" value="Yellow" id="size1_1_1_2"/>
                                                                <label for="size1_1_1_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_1" value="Red" id="size1_1_1_3"/>
                                                                <label for="size1_1_1_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/6.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/6.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plNew float-end">New</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">19 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details1.html">Luxury maroon sweater</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$49</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_1_2" value="Blue" id="color_1_2_1_blue"/>
                                                                <label for="color_1_2_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_1_2" value="Yellow" id="color_1_2_2_blue"/>
                                                                <label for="color_1_2_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_1_2" value="Red" id="color_1_2_3_blue"/>
                                                                <label for="color_1_2_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_2" value="Blue" id="size1_1_2_1"/>
                                                                <label for="size1_1_2_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_2" value="Yellow" id="size1_1_2_2"/>
                                                                <label for="size1_1_2_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_2" value="Red" id="size1_1_2_3"/>
                                                                <label for="size1_1_2_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/7.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/7.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plDis">-89</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details2.html">Lineal louse cotton tops</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$89</ins>
                                                        <del>$99</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_1_3" value="Blue" id="color_1_3_1_blue"/>
                                                                <label for="color_1_3_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_1_3" value="Yellow" id="color_1_3_2_blue"/>
                                                                <label for="color_1_3_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_1_3" value="Red" id="color_1_3_3_blue"/>
                                                                <label for="color_1_3_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_3" value="Blue" id="size1_1_3_1"/>
                                                                <label for="size1_1_3_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_3" value="Yellow" id="size1_1_3_2"/>
                                                                <label for="size1_1_3_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_3" value="Red" id="size1_1_3_3"/>
                                                                <label for="size1_1_3_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/8.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/8.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">13 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Men’s black stylish half shirt</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$129</ins>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_1_4" value="Blue" id="color_1_4_1_blue"/>
                                                                <label for="color_1_4_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_1_4" value="Yellow" id="color_1_4_2_blue"/>
                                                                <label for="color_1_4_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_1_4" value="Red" id="color_1_4_3_blue"/>
                                                                <label for="color_1_4_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_4" value="Blue" id="size1_1_4_1"/>
                                                                <label for="size1_1_4_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_4" value="Yellow" id="size1_1_4_2"/>
                                                                <label for="size1_1_4_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_4" value="Red" id="size1_1_4_3"/>
                                                                <label for="size1_1_4_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/9.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/9.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plHot float-end">Hot</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details1.html">Mini sleeve gray t-shirt</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$39</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_1_5" value="Blue" id="color_1_5_1_blue"/>
                                                                <label for="color_1_5_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_1_5" value="Yellow" id="color_1_5_2_blue"/>
                                                                <label for="color_1_5_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_1_5" value="Red" id="color_1_5_3_blue"/>
                                                                <label for="color_1_5_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_5" value="Blue" id="size1_1_5_1"/>
                                                                <label for="size1_1_5_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_5" value="Yellow" id="size1_1_5_2"/>
                                                                <label for="size1_1_5_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_5" value="Red" id="size1_1_5_3"/>
                                                                <label for="size1_1_5_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/10.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/10.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">18 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Polyester silk blazer suit for men</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$499</ins>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_1_6" value="Blue" id="color_1_6_1_blue"/>
                                                                <label for="color_1_6_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_1_6" value="Yellow" id="color_1_6_2_blue"/>
                                                                <label for="color_1_6_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_1_6" value="Red" id="color_1_6_3_blue"/>
                                                                <label for="color_1_6_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_6" value="Blue" id="size1_1_6_1"/>
                                                                <label for="size1_1_6_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_6" value="Yellow" id="size1_1_6_2"/>
                                                                <label for="size1_1_6_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_6" value="Red" id="size1_1_6_3"/>
                                                                <label for="size1_1_6_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/11.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/11.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plSale float-end">sale</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">10 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details1.html">Women’s long cardigans</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$89</ins>
                                                        <del>$99</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_1_7" value="Blue" id="color_1_7_1_blue"/>
                                                                <label for="color_1_7_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_1_7" value="Yellow" id="color_1_7_2_blue"/>
                                                                <label for="color_1_7_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_1_7" value="Red" id="color_1_7_3_blue"/>
                                                                <label for="color_1_7_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_7" value="Blue" id="size1_1_7_1"/>
                                                                <label for="size1_1_7_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_7" value="Yellow" id="size1_1_7_2"/>
                                                                <label for="size1_1_7_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_7" value="Red" id="size1_1_7_3"/>
                                                                <label for="size1_1_7_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/12.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/12.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details2.html">Ulina bag for women</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$49</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_1_8" value="Blue" id="color_1_8_1_blue"/>
                                                                <label for="color_1_8_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_1_8" value="Yellow" id="color_1_8_2_blue"/>
                                                                <label for="color_1_8_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_1_8" value="Red" id="color_1_8_3_blue"/>
                                                                <label for="color_1_8_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_8" value="Blue" id="size1_1_8_1"/>
                                                                <label for="size1_1_8_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_8" value="Yellow" id="size1_1_8_2"/>
                                                                <label for="size1_1_8_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_1_8" value="Red" id="size1_1_8_3"/>
                                                                <label for="size1_1_8_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="women-tab-pane" role="tabpanel" aria-labelledby="women-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/9.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/9.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plHot float-end">Hot</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details1.html">Mini sleeve gray t-shirt</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$39</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_2_5" value="Blue" id="color_2_5_1_blue"/>
                                                                <label for="color_2_5_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_2_5" value="Yellow" id="color_2_5_2_blue"/>
                                                                <label for="color_2_5_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_2_5" value="Red" id="color_2_5_3_blue"/>
                                                                <label for="color_2_5_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_5" value="Blue" id="size1_2_5_1"/>
                                                                <label for="size1_2_5_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_5" value="Yellow" id="size1_2_5_2"/>
                                                                <label for="size1_2_5_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_5" value="Red" id="size1_2_5_3"/>
                                                                <label for="size1_2_5_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/10.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/10.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">18 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Polyester silk blazer suit for men</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$499</ins>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_2_6" value="Blue" id="color_2_6_1_blue"/>
                                                                <label for="color_2_6_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_2_6" value="Yellow" id="color_2_6_2_blue"/>
                                                                <label for="color_2_6_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_2_6" value="Red" id="color_2_6_3_blue"/>
                                                                <label for="color_2_6_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_6" value="Blue" id="size1_2_6_1"/>
                                                                <label for="size1_2_6_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_6" value="Yellow" id="size1_2_6_2"/>
                                                                <label for="size1_2_6_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_6" value="Red" id="size1_2_6_3"/>
                                                                <label for="size1_2_6_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/11.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/11.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plSale float-end">sale</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">10 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details1.html">Women’s long cardigans</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$89</ins>
                                                        <del>$99</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_2_7" value="Blue" id="color_2_7_1_blue"/>
                                                                <label for="color_2_7_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_2_7" value="Yellow" id="color_2_7_2_blue"/>
                                                                <label for="color_2_7_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_2_7" value="Red" id="color_2_7_3_blue"/>
                                                                <label for="color_2_7_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_7" value="Blue" id="size1_2_7_1"/>
                                                                <label for="size1_2_7_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_7" value="Yellow" id="size1_2_7_2"/>
                                                                <label for="size1_2_7_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_7" value="Red" id="size1_2_7_3"/>
                                                                <label for="size1_2_7_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/12.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/12.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details2.html">Ulina bag for women</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$49</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_2_8" value="Blue" id="color_2_8_1_blue"/>
                                                                <label for="color_2_8_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_2_8" value="Yellow" id="color_2_8_2_blue"/>
                                                                <label for="color_2_8_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_2_8" value="Red" id="color_2_8_3_blue"/>
                                                                <label for="color_2_8_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_8" value="Blue" id="size1_2_8_1"/>
                                                                <label for="size1_2_8_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_8" value="Yellow" id="size1_2_8_2"/>
                                                                <label for="size1_2_8_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_8" value="Red" id="size1_2_8_3"/>
                                                                <label for="size1_2_8_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/5.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/5.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plDis">- $29</span>
                                                        <span class="plSale">Sale</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">10 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Stylish white leather bag</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$29</ins>
                                                        <del>$56</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_2_1" value="Blue" id="color_2_1_1_blue"/>
                                                                <label for="color_2_1_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_2_1" value="Yellow" id="color_2_1_2_blue"/>
                                                                <label for="color_2_1_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_2_1" value="Red" id="color_2_1_3_blue"/>
                                                                <label for="color_2_1_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_1" value="Blue" id="size1_2_1_1"/>
                                                                <label for="size1_2_1_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_1" value="Yellow" id="size1_2_1_2"/>
                                                                <label for="size1_2_1_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_1" value="Red" id="size1_2_1_3"/>
                                                                <label for="size1_2_1_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/6.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/6.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plNew float-end">New</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">19 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details1.html">Luxury maroon sweater</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$49</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_2_2" value="Blue" id="color_2_2_1_blue"/>
                                                                <label for="color_2_2_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_2_2" value="Yellow" id="color_2_2_2_blue"/>
                                                                <label for="color_2_2_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_2_2" value="Red" id="color_2_2_3_blue"/>
                                                                <label for="color_2_2_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_2" value="Blue" id="size1_2_2_1"/>
                                                                <label for="size1_2_2_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_2" value="Yellow" id="size1_2_2_2"/>
                                                                <label for="size1_2_2_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_2" value="Red" id="size1_2_2_3"/>
                                                                <label for="size1_2_2_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/7.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/7.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plDis">-89</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details2.html">Lineal louse cotton tops</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$89</ins>
                                                        <del>$99</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_2_3" value="Blue" id="color_2_3_1_blue"/>
                                                                <label for="color_2_3_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_2_3" value="Yellow" id="color_2_3_2_blue"/>
                                                                <label for="color_2_3_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_2_3" value="Red" id="color_2_3_3_blue"/>
                                                                <label for="color_2_3_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_3" value="Blue" id="size1_2_3_1"/>
                                                                <label for="size1_2_3_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_3" value="Yellow" id="size1_2_3_2"/>
                                                                <label for="size1_2_3_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_3" value="Red" id="size1_2_3_3"/>
                                                                <label for="size1_2_3_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/8.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/8.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">13 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Men’s black stylish half shirt</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$129</ins>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_2_4" value="Blue" id="color_2_4_1_blue"/>
                                                                <label for="color_2_4_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_2_4" value="Yellow" id="color_2_4_2_blue"/>
                                                                <label for="color_2_4_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_2_4" value="Red" id="color_2_4_3_blue"/>
                                                                <label for="color_2_4_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_4" value="Blue" id="size1_2_4_1"/>
                                                                <label for="size1_2_4_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_4" value="Yellow" id="size1_2_4_2"/>
                                                                <label for="size1_2_4_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_2_4" value="Red" id="size1_2_4_3"/>
                                                                <label for="size1_2_4_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="kids-tab-pane" role="tabpanel" aria-labelledby="kids-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/7.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/7.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plDis">-89</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details1.html">Lineal louse cotton tops</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$89</ins>
                                                        <del>$99</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_3_3" value="Blue" id="color_3_3_1_blue"/>
                                                                <label for="color_3_3_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_3_3" value="Yellow" id="color_3_3_2_blue"/>
                                                                <label for="color_3_3_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_3_3" value="Red" id="color_3_3_3_blue"/>
                                                                <label for="color_3_3_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_3" value="Blue" id="size1_3_3_1"/>
                                                                <label for="size1_3_3_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_3" value="Yellow" id="size1_3_3_2"/>
                                                                <label for="size1_3_3_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_3" value="Red" id="size1_3_3_3"/>
                                                                <label for="size1_3_3_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/8.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/8.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">13 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Men’s black stylish half shirt</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$129</ins>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_3_4" value="Blue" id="color_3_4_1_blue"/>
                                                                <label for="color_3_4_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_3_4" value="Yellow" id="color_3_4_2_blue"/>
                                                                <label for="color_3_4_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_3_4" value="Red" id="color_3_4_3_blue"/>
                                                                <label for="color_3_4_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_4" value="Blue" id="size1_3_4_1"/>
                                                                <label for="size1_3_4_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_4" value="Yellow" id="size1_3_4_2"/>
                                                                <label for="size1_3_4_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_4" value="Red" id="size1_3_4_3"/>
                                                                <label for="size1_3_4_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/5.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/5.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plDis">- $29</span>
                                                        <span class="plSale">Sale</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">10 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details1.html">Stylish white leather bag</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$29</ins>
                                                        <del>$56</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_3_1" value="Blue" id="color_3_1_1_blue"/>
                                                                <label for="color_3_1_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_3_1" value="Yellow" id="color_3_1_2_blue"/>
                                                                <label for="color_3_1_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_3_1" value="Red" id="color_3_1_3_blue"/>
                                                                <label for="color_3_1_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_1" value="Blue" id="size1_3_1_1"/>
                                                                <label for="size1_3_1_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_1" value="Yellow" id="size1_3_1_2"/>
                                                                <label for="size1_3_1_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_1" value="Red" id="size1_3_1_3"/>
                                                                <label for="size1_3_1_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/6.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/6.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plNew float-end">New</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">19 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Luxury maroon sweater</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$49</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_3_2" value="Blue" id="color_3_2_1_blue"/>
                                                                <label for="color_3_2_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_3_2" value="Yellow" id="color_3_2_2_blue"/>
                                                                <label for="color_3_2_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_3_2" value="Red" id="color_3_2_3_blue"/>
                                                                <label for="color_3_2_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_2" value="Blue" id="size1_3_2_1"/>
                                                                <label for="size1_3_2_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_2" value="Yellow" id="size1_3_2_2"/>
                                                                <label for="size1_3_2_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_2" value="Red" id="size1_3_2_3"/>
                                                                <label for="size1_3_2_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/11.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/11.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plSale float-end">sale</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">10 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details1.html">Women’s long cardigans</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$89</ins>
                                                        <del>$99</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_3_7" value="Blue" id="color_3_7_1_blue"/>
                                                                <label for="color_3_7_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_3_7" value="Yellow" id="color_3_7_2_blue"/>
                                                                <label for="color_3_7_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_3_7" value="Red" id="color_3_7_3_blue"/>
                                                                <label for="color_3_7_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_7" value="Blue" id="size1_3_7_1"/>
                                                                <label for="size1_3_7_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_7" value="Yellow" id="size1_3_7_2"/>
                                                                <label for="size1_3_7_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_7" value="Red" id="size1_3_7_3"/>
                                                                <label for="size1_3_7_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/12.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/12.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details2.html">Ulina bag for women</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$49</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_3_8" value="Blue" id="color_3_8_1_blue"/>
                                                                <label for="color_3_8_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_3_8" value="Yellow" id="color_3_8_2_blue"/>
                                                                <label for="color_3_8_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_3_8" value="Red" id="color_3_8_3_blue"/>
                                                                <label for="color_3_8_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_8" value="Blue" id="size1_3_8_1"/>
                                                                <label for="size1_3_8_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_8" value="Yellow" id="size1_3_8_2"/>
                                                                <label for="size1_3_8_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_8" value="Red" id="size1_3_8_3"/>
                                                                <label for="size1_3_8_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/9.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/9.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plHot float-end">Hot</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details1.html">Mini sleeve gray t-shirt</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$39</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_3_5" value="Blue" id="color_3_5_1_blue"/>
                                                                <label for="color_3_5_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_3_5" value="Yellow" id="color_3_5_2_blue"/>
                                                                <label for="color_3_5_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_3_5" value="Red" id="color_3_5_3_blue"/>
                                                                <label for="color_3_5_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_5" value="Blue" id="size1_3_5_1"/>
                                                                <label for="size1_3_5_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_5" value="Yellow" id="size1_3_5_2"/>
                                                                <label for="size1_3_5_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_5" value="Red" id="size1_3_5_3"/>
                                                                <label for="size1_3_5_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/10.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/10.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">18 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Polyester silk blazer suit for men</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$499</ins>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_3_6" value="Blue" id="color_3_6_1_blue"/>
                                                                <label for="color_3_6_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_3_6" value="Yellow" id="color_3_6_2_blue"/>
                                                                <label for="color_3_6_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_3_6" value="Red" id="color_3_6_3_blue"/>
                                                                <label for="color_3_6_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_6" value="Blue" id="size1_3_6_1"/>
                                                                <label for="size1_3_6_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_6" value="Yellow" id="size1_3_6_2"/>
                                                                <label for="size1_3_6_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_3_6" value="Red" id="size1_3_6_3"/>
                                                                <label for="size1_3_6_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="accessories-tab-pane" role="tabpanel" aria-labelledby="accessories-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/7.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/7.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plDis">-89</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details1.html">Lineal louse cotton tops</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$89</ins>
                                                        <del>$99</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_4_3" value="Blue" id="color_4_3_1_blue"/>
                                                                <label for="color_4_3_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_4_3" value="Yellow" id="color_4_3_2_blue"/>
                                                                <label for="color_4_3_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_4_3" value="Red" id="color_4_3_3_blue"/>
                                                                <label for="color_4_3_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_3" value="Blue" id="size1_4_3_1"/>
                                                                <label for="size1_4_3_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_3" value="Yellow" id="size1_4_3_2"/>
                                                                <label for="size1_4_3_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_3" value="Red" id="size1_4_3_3"/>
                                                                <label for="size1_4_3_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/5.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/5.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plDis">- $29</span>
                                                        <span class="plSale">Sale</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">10 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details1.html">Stylish white leather bag</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$29</ins>
                                                        <del>$56</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_4_1" value="Blue" id="color_4_1_1_blue"/>
                                                                <label for="color_4_1_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_4_1" value="Yellow" id="color_4_1_2_blue"/>
                                                                <label for="color_4_1_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_4_1" value="Red" id="color_4_1_3_blue"/>
                                                                <label for="color_4_1_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_1" value="Blue" id="size1_4_1_1"/>
                                                                <label for="size1_4_1_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_1" value="Yellow" id="size1_4_1_2"/>
                                                                <label for="size1_4_1_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_1" value="Red" id="size1_4_1_3"/>
                                                                <label for="size1_4_1_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/6.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/6.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plNew float-end">New</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">19 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Luxury maroon sweater</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$49</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_4_2" value="Blue" id="color_4_2_1_blue"/>
                                                                <label for="color_4_2_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_4_2" value="Yellow" id="color_4_2_2_blue"/>
                                                                <label for="color_4_2_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_4_2" value="Red" id="color_4_2_3_blue"/>
                                                                <label for="color_4_2_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_2" value="Blue" id="size1_4_2_1"/>
                                                                <label for="size1_4_2_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_2" value="Yellow" id="size1_4_2_2"/>
                                                                <label for="size1_4_2_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_2" value="Red" id="size1_4_2_3"/>
                                                                <label for="size1_4_2_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/8.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/8.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">13 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Men’s black stylish half shirt</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$129</ins>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_4_4" value="Blue" id="color_4_4_1_blue"/>
                                                                <label for="color_4_4_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_4_4" value="Yellow" id="color_4_4_2_blue"/>
                                                                <label for="color_4_4_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_4_4" value="Red" id="color_4_4_3_blue"/>
                                                                <label for="color_4_4_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_4" value="Blue" id="size1_4_4_1"/>
                                                                <label for="size1_4_4_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_4" value="Yellow" id="size1_4_4_2"/>
                                                                <label for="size1_4_4_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_4" value="Red" id="size1_4_4_3"/>
                                                                <label for="size1_4_4_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/11.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/11.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plSale float-end">sale</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">10 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Women’s long cardigans</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$89</ins>
                                                        <del>$99</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_4_7" value="Blue" id="color_4_7_1_blue"/>
                                                                <label for="color_4_7_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_4_7" value="Yellow" id="color_4_7_2_blue"/>
                                                                <label for="color_4_7_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_4_7" value="Red" id="color_4_7_3_blue"/>
                                                                <label for="color_4_7_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_7" value="Blue" id="size1_4_7_1"/>
                                                                <label for="size1_4_7_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_7" value="Yellow" id="size1_4_7_2"/>
                                                                <label for="size1_4_7_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_7" value="Red" id="size1_4_7_3"/>
                                                                <label for="size1_4_7_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/12.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/12.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details2.html">Ulina bag for women</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$49</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_4_8" value="Blue" id="color_4_8_1_blue"/>
                                                                <label for="color_4_8_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_4_8" value="Yellow" id="color_4_8_2_blue"/>
                                                                <label for="color_4_8_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_4_8" value="Red" id="color_4_8_3_blue"/>
                                                                <label for="color_4_8_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_8" value="Blue" id="size1_4_8_1"/>
                                                                <label for="size1_4_8_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_8" value="Yellow" id="size1_4_8_2"/>
                                                                <label for="size1_4_8_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_8" value="Red" id="size1_4_8_3"/>
                                                                <label for="size1_4_8_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01 pi01NoRating">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/9.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/9.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        <span class="plHot float-end">Hot</span>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="shop_details2.html">Mini sleeve gray t-shirt</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$39</ins>
                                                        <del>$60</del>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_4_5" value="Blue" id="color_4_5_1_blue"/>
                                                                <label for="color_4_5_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_4_5" value="Yellow" id="color_4_5_2_blue"/>
                                                                <label for="color_4_5_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_4_5" value="Red" id="color_4_5_3_blue"/>
                                                                <label for="color_4_5_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_5" value="Blue" id="size1_4_5_1"/>
                                                                <label for="size1_4_5_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_5" value="Yellow" id="size1_4_5_2"/>
                                                                <label for="size1_4_5_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_5" value="Red" id="size1_4_5_3"/>
                                                                <label for="size1_4_5_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ asset('assets/Client/images/products/10.jpg') }}" alt="Ulina Product"/>
                                                    <img src="{{ asset('assets/Client/images/products/10.1.jpg') }}" alt="Ulina Product"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <div class="productRatings">
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="ratingCounts">18 Reviews</div>
                                                    </div>
                                                    <h3><a href="shop_details2.html">Polyester silk blazer suit for men</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>$499</ins>
                                                    </div>
                                                    <div class="pi01Variations">
                                                        <div class="pi01VColor">
                                                            <div class="pi01VCItem">
                                                                <input checked type="radio" name="color_4_6" value="Blue" id="color_4_6_1_blue"/>
                                                                <label for="color_4_6_1_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem yellows">
                                                                <input type="radio" name="color_4_6" value="Yellow" id="color_4_6_2_blue"/>
                                                                <label for="color_4_6_2_blue"></label>
                                                            </div>
                                                            <div class="pi01VCItem reds">
                                                                <input type="radio" name="color_4_6" value="Red" id="color_4_645678_3_blue"/>
                                                                <label for="color_4_645678_3_blue"></label>
                                                            </div>
                                                        </div>
                                                        <div class="pi01VSize">
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_6" value="Blue" id="size1_4_6_1"/>
                                                                <label for="size1_4_6_1">S</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_6" value="Yellow" id="size1_4_6_2"/>
                                                                <label for="size1_4_6_2">M</label>
                                                            </div>
                                                            <div class="pi01VSItem">
                                                                <input type="radio" name="size_4_6" value="Red" id="size1_4_6_3"/>
                                                                <label for="size1_4_6_3">XL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>  --}}
        <!-- END: Popular Products Section -->

        <!-- BEGIN: Lookbook Section 2 -->
         {{-- <section class="lookbookSection2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="lookBook01 lb01M6 overLayAnim02">
                            <div class="lbContent">
                                <h3>Get 40% Off</h3>
                                <h2>Women’s New Collection</h2>
                                <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop Now</a>
                            </div>
                            <img src="{{ asset('assets/Client/images/home1/8.jpg') }}" alt="Women’s New Collection">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="lookBook01 lb01M6 overLayAnim02">
                            <div class="lbContent">
                                <h3>Stay Upto Date</h3>
                                <h2>Men’s Trendy Fashion</h2>
                                <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop Now</a>
                            </div>
                            <img src="{{ asset('assets/Client/images/home1/9.jpg') }}" alt="Men’s Trendy Fashion">
                        </div>
                    </div>
                </div>
            </div>
        </section>  --}}
        <!-- END: Lookbook Section 2 -->

        <!-- BEGIN: Category Section -->
        <<section class="categorySection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="secTitle">Shop By Category</h2>
                        <p class="secDesc">Showing our latest arrival on this summer</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="categoryCarousel owl-carousel">
                            <div class="categoryItem01 text-center">
                                <div class="ci01Thumb">
                                    <img src="{{ asset('assets/Client/images/categoy/1.jpg') }}" alt="Cloths"/>
                                </div>
                                <h3><a href="shop_full_width.html">Cloths</a></h3>
                                <p>192 Items</p>
                            </div>
                            <div class="categoryItem01 text-center">
                                <div class="ci01Thumb">
                                    <img src="{{ asset('assets/Client/images/categoy/2.jpg') }}" alt="Bags"/>
                                </div>
                                <h3><a href="shop_left_sidebar.html">Bags</a></h3>
                                <p>139 Items</p>
                            </div>
                            <div class="categoryItem01 text-center">
                                <div class="ci01Thumb">
                                    <img src="{{ asset('assets/Client/images/categoy/3.jpg') }}" alt="Watches"/>
                                </div>
                                <h3><a href="shop_right_sidebar.html">Watches</a></h3>
                                <p>162 Items</p>
                            </div>
                            <div class="categoryItem01 text-center">
                                <div class="ci01Thumb">
                                    <img src="{{ asset('assets/Client/images/categoy/4.jpg') }}" alt="Jewellery"/>
                                </div>
                                <h3><a href="shop_full_width.html">Jewellery</a></h3>
                                <p>187 Items</p>
                            </div>
                            <div class="categoryItem01 text-center">
                                <div class="ci01Thumb">
                                    <img src="{{ asset('assets/Client/images/categoy/5.jpg') }}" alt="Women"/>
                                </div>
                                <h3><a href="shop_right_sidebar.html">Women</a></h3>
                                <p>362 Items</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>  
        <!-- END: Category Section -->

        <!-- BEGIN: Testimonial Section -->
        <section class="testimonialSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-xl-3">
                        <div class="testimoniLeft">
                            <h2 class="secTitle">What Customers Say About Us</h2>
                            <p class="secDesc">Bobore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat ion ullamco laboris</p>
                            <div class="testimonalNav">
                                <button class="tprev"><i class="fa-solid fa-angle-left"></i></button>
                                <button class="tnext"><i class="fa-solid fa-angle-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-9">
                        <div class="testimonialSliderWrap">
                            <div class="testimonialCarousel owl-carousel">
                                <div class="testimonialItem01">
                                    <div class="ti01Header clearfix">
                                        <i class="ulina-quote"></i>
                                        <div class="ti01Rating float-end">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <div class="ti01Content">
                                        Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor incididunt ut labore et doleore magna
                                        aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut aliquip.
                                    </div>
                                    <div class="ti01Author">
                                        <img src="{{ asset('assets/Client/images/author/1.jpg') }}" alt="Sanjida Ema"/>
                                        <h3>Sanjida Ema</h3>
                                        <span>Journalist</span>
                                    </div>
                                </div>
                                <div class="testimonialItem01">
                                    <div class="ti01Header clearfix">
                                        <i class="ulina-quote"></i>
                                        <div class="ti01Rating float-end">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <div class="ti01Content">
                                        Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor incididunt ut labore et doleore magna
                                        aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut aliquip.
                                    </div>
                                    <div class="ti01Author">
                                        <img src="{{ asset('assets/Client/images/author/2.jpg') }}" alt="Mike Anderson"/>
                                        <h3>Mike Anderson</h3>
                                        <span>Web Developer</span>
                                    </div>
                                </div>
                                <div class="testimonialItem01">
                                    <div class="ti01Header clearfix">
                                        <i class="ulina-quote"></i>
                                        <div class="ti01Rating float-end">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <div class="ti01Content">
                                        Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor incididunt ut labore et doleore magna
                                        aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut aliquip.
                                    </div>
                                    <div class="ti01Author">
                                        <img src="{{ asset('assets/Client/images/author/3.png') }}" alt="Nelson Rich"/>
                                        <h3>Nelson Rich</h3>
                                        <span>Designer</span>
                                    </div>
                                </div>
                                <div class="testimonialItem01">
                                    <div class="ti01Header clearfix">
                                        <i class="ulina-quote"></i>
                                        <div class="ti01Rating float-end">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <div class="ti01Content">
                                        Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor incididunt ut labore et doleore magna
                                        aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut aliquip.
                                    </div>
                                    <div class="ti01Author">
                                        <img src="{{ asset('assets/Client/images/author/4.png') }}" alt="Nelson Rich"/>
                                        <h3>Mark Smith</h3>
                                        <span>Marketer</span>
                                    </div>
                                </div>
                                <div class="testimonialItem01">
                                    <div class="ti01Header clearfix">
                                        <i class="ulina-quote"></i>
                                        <div class="ti01Rating float-end">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <div class="ti01Content">
                                        Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor incididunt ut labore et doleore magna
                                        aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut aliquip.
                                    </div>
                                    <div class="ti01Author">
                                        <img src="{{ asset('assets/Client/images/author/5.png') }}" alt="Sarah Jones"/>
                                        <h3>Sarah Jones</h3>
                                        <span>Researcher</span>
                                    </div>
                                </div>
                                <div class="testimonialItem01">
                                    <div class="ti01Header clearfix">
                                        <i class="ulina-quote"></i>
                                        <div class="ti01Rating float-end">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <div class="ti01Content">
                                        Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor incididunt ut labore et doleore magna
                                        aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut aliquip.
                                    </div>
                                    <div class="ti01Author">
                                        <img src="{{ asset('assets/Client/images/author/6.png') }}" alt="Sarah Jones"/>
                                        <h3>John Anderson</h3>
                                        <span>Blogger</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> 
        <!-- END: Testimonial Section -->

        <!-- BEGIN: Blog Section -->
         <section class="blogSection py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="secTitle">Bài viết mới</h2>
                <p class="secDesc">Tin tức và chia sẻ gần đây</p>
            </div>
            <div class="col-md-6 text-end pdt34">
                <a href="{{ route('client.blogs.index') }}" class="ulinaBTN2"><span>Xem tất cả</span></a>
            </div>
        </div>

        <div class="row">
            @foreach($blogs as $blog)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="blogItem02 h-100 shadow-sm border rounded overflow-hidden bg-white">
                        <a href="{{ route('client.blogs.show', $blog->slug) }}">
                            <img src="{{ $blog->first_image_from_content ?? asset('images/default-thumbnail.jpg') }}"
                                 alt="{{ $blog->title }}"
                                 class="w-100"
                                 style="height: 220px; object-fit: cover; border-radius: .5rem .5rem 0 0;">
                        </a>
                        <div class="p-3">
                            <div class="bi01Meta mb-2 text-muted" style="font-size: 0.9rem;">
                                <i class="fa-solid fa-clock me-1"></i> {{ $blog->created_at->format('d/m/Y') }}
                            </div>
                            <h3 class="mb-2" style="font-size: 1rem; line-height: 1.4em; height: 2.8em; overflow: hidden;">
                                <a href="{{ route('client.blogs.show', $blog->slug) }}" class="text-dark text-decoration-none">
                                    {{ $blog->title }}
                                </a>
                            </h3>
                            <a href="{{ route('client.blogs.show', $blog->slug) }}" class="ulinaLink text-primary fw-semibold">
                                <i class="fa-solid fa-angle-right me-1"></i> Xem thêm
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


 
        <!-- END: Blog Section -->

        <!-- BEGIN: Instagram Section -->
        <!-- <section class="instagramSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="secTitle">Khách Hàng Thân Thiết</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="instagramSlider owl-carousel">
                            <a href="images/instagram/1_f.jpg" class="instagramPhoto imgPopup">
                                <img src="{{ asset('assets/Client/images/instagram/1.jpg') }}" alt="Ulina Instagram"/>
                            </a>
                            <a href="images/instagram/2_f.jpg" class="instagramPhoto imgPopup">
                                <img src="{{ asset('assets/Client/images/instagram/2.jpg') }}" alt="Ulina Instagram"/>
                            </a>
                            <a href="images/instagram/3_f.jpg" class="instagramPhoto imgPopup">
                                <img src="{{ asset('assets/Client/images/instagram/3.jpg') }}" alt="Ulina Instagram"/>
                            </a>
                            <a href="images/instagram/4_f.jpg" class="instagramPhoto imgPopup">
                                <img src="{{ asset('assets/Client/images/instagram/4.jpg') }}" alt="Ulina Instagram"/>
                            </a>
                            <a href="images/instagram/5_f.jpg" class="instagramPhoto imgPopup">
                                <img src="{{ asset('assets/Client/images/instagram/5.jpg') }}" alt="Ulina Instagram"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- END: Instagram Section -->

        <!-- BEGIN: Brand Section -->
        <!-- <section class="brandSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="clientLogoSlider owl-carousel">
                            <a class="clientLogo" href="javascript:void(0);">
                                <img src="{{ asset('assets/Client/images/clients/1h.png') }}" alt="Ulina Brand">
                                <img src="{{ asset('assets/Client/images/clients/1.png') }}" alt="Ulina Brand">
                            </a>
                            <a class="clientLogo" href="javascript:void(0);">
                                <img src="{{ asset('assets/Client/images/clients/2h.png') }}" alt="Ulina Brand">
                                <img src="{{ asset('assets/Client/images/clients/2.png') }}" alt="Ulina Brand">
                            </a>
                            <a class="clientLogo" href="javascript:void(0);">
                                <img src="{{ asset('assets/Client/images/clients/3h.png') }}" alt="Ulina Brand">
                                <img src="{{ asset('assets/Client/images/clients/3.png') }}" alt="Ulina Brand">
                            </a>
                            <a class="clientLogo" href="javascript:void(0);">
                                <img src="{{ asset('assets/Client/images/clients/4h.png') }}" alt="Ulina Brand">
                                <img src="{{ asset('assets/Client/images/clients/4.png') }}" alt="Ulina Brand">
                            </a>
                            <a class="clientLogo" href="javascript:void(0);">
                                <img src="{{ asset('assets/Client/images/clients/5h.png') }}" alt="Ulina Brand">
                                <img src="{{ asset('assets/Client/images/clients/5.png') }}" alt="Ulina Brand">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- END: Brand Section -->

        <!-- BEGIN: Footer Section -->

        <!-- END: Footer Section -->

        <!-- BEGIN: Site Info Section -->
        <!-- <section class="siteInfoSection">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="siteInfo">
                            All rights reserved &nbsp;<a href="index.html">Ulina</a>&nbsp;&nbsp;&copy;&nbsp;&nbsp;2022
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="footerNav">
                            <ul>
                                <li><a href="javascript:void(0);">Terms & Condition</a></li>
                                <li><a href="javascript:void(0);">Privacy Policy</a></li>
                                <li><a href="javascript:void(0);">Legal</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- END: Site Info Section -->

        <!-- BEGIN: Back To Top -->
        <!-- <a href="javascript:void(0);" id="backtotop"><i class="fa-solid fa-angles-up"></i></a> -->
        <!-- END: Back To Top -->

        <!-- BEGIN: Product QuickView  -->
        <!-- <div class="modal fade productQuickView" id="productQuickView" tabindex="-1" data-aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="quickViewCloser" data-bs-dismiss="modal" aria-label="Close"><span></span></button>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="productGalleryWrap">
                                    <div class="productGalleryPopup">
                                        <div class="pgImage">
                                            <img src="{{ asset('assets/Client/images/product_details/p1.jpg') }}" alt="Product Image"/>
                                        </div>
                                        <div class="pgImage">
                                            <img src="{{ asset('assets/Client/images/product_details/p2.jpg') }}" alt="Product Image"/>
                                        </div>
                                        <div class="pgImage">
                                            <img src="{{ asset('assets/Client/images/product_details/p3.jpg') }}" alt="Product Image"/>
                                        </div>
                                        <div class="pgImage">
                                            <img src="{{ asset('assets/Client/images/product_details/p4.jpg') }}" alt="Product Image"/>
                                        </div>
                                        <div class="pgImage">
                                            <img src="{{ asset('assets/Client/images/product_details/p5.jpg') }}" alt="Product Image"/>
                                        </div>
                                    </div>
                                    <div class="productGalleryThumbWrap">
                                        <div class="productGalleryThumbPopup">
                                            <div class="pgtImage">
                                                <img src="{{ asset('assets/Client/images/product_details/t1.jpg') }}" alt="Product Image"/>
                                            </div>
                                            <div class="pgtImage">
                                                <img src="{{ asset('assets/Client/images/product_details/t2.jpg') }}" alt="Product Image"/>
                                            </div>
                                            <div class="pgtImage">
                                                <img src="{{ asset('assets/Client/images/product_details/t3.jpg') }}" alt="Product Image"/>
                                            </div>
                                            <div class="pgtImage">
                                                <img src="{{ asset('assets/Client/images/product_details/t4.jpg') }}" alt="Product Image"/>
                                            </div>
                                            <div class="pgtImage">
                                                <img src="{{ asset('assets/Client/images/product_details/t5.jpg') }}" alt="Product Image"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="productContent">
                                    <div class="pcCategory">
                                        <a href="shop_right_sidebar.html">Fashion</a>, <a href="shop_left_sidebar.html">Sports</a>
                                    </div>
                                    <h2><a href="shop_details1.html">Ulina luxurious shirt for men</a></h2>
                                    <div class="pi01Price">
                                        <ins>$108</ins>
                                        <del>$120</del>
                                    </div>
                                    <div class="productRadingsStock clearfix">
                                        <div class="productRatings float-start">
                                            <div class="productRatingWrap">
                                                <div class="star-rating"><span></span></div>
                                            </div>
                                            <div class="ratingCounts">52 Reviews</div>
                                        </div>
                                        <div class="productStock float-end">
                                            <span>Available :</span> 12
                                        </div>
                                    </div>
                                    <div class="pcExcerpt">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusncididunt ut labo re et dolore magna aliqua. Ut enim ad minim
                                        veniam
                                    </div>
                                    <div class="pcVariations">
                                        <div class="pcVariation">
                                            <span>Color</span>
                                            <div class="pcvContainer">
                                                <div class="pi01VCItem">
                                                    <input checked type="radio" name="color_4_6" value="Blue" id="color_4_634_1_blue"/>
                                                    <label for="color_4_634_1_blue"></label>
                                                </div>
                                                <div class="pi01VCItem yellows">
                                                    <input type="radio" name="color_4_6" value="Yellow" id="color_4_6sdf_2_blue"/>
                                                    <label for="color_4_6sdf_2_blue"></label>
                                                </div>
                                                <div class="pi01VCItem reds">
                                                    <input type="radio" name="color_4_6" value="Red" id="color_4_6_3_blue"/>
                                                    <label for="color_4_6_3_blue"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pcVariation pcv2">
                                            <span>Size</span>
                                            <div class="pcvContainer">
                                                <div class="pswItem">
                                                    <input checked="" type="radio" name="ws_1" value="S" id="ws_1_s">
                                                    <label for="ws_1_s">S</label>
                                                </div>
                                                <div class="pswItem">
                                                    <input type="radio" name="ws_1" value="M" id="ws_1_m">
                                                    <label for="ws_1_m">M</label>
                                                </div>
                                                <div class="pswItem">
                                                    <input type="radio" name="ws_1" value="L" id="ws_1_l">
                                                    <label for="ws_1_l">L</label>
                                                </div>
                                                <div class="pswItem">
                                                    <input type="radio" name="ws_1" value="XL" id="ws_1_xl">
                                                    <label for="ws_1_xl">XL</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pcBtns">
                                        <div class="quantity clearfix">
                                            <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                                            <input type="number" class="carqty input-text qty text" name="quantity" value="01">
                                            <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                                        </div>
                                        <button type="submit" class="ulinaBTN"><span>Add to Cart</span></button>
                                    </div>
                                    <div class="pcMeta">
                                        <p>
                                            <span>Sku</span>
                                            <a href="javascript:void(0);">3489 JE0765</a>
                                        </p>
                                        <p class="pcmTags">
                                            <span>Tags:</span>
                                            <a href="javascript:void(0);">Fashion</a>, <a href="javascript:void(0);">Bags</a>, <a href="javascript:void(0);">Girls</a>
                                        </p>
                                        <p class="pcmSocial">
                                            <span>Share</span>
                                            <a class="fac" href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a>
                                            <a class="twi" href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a>
                                            <a class="lin" href="javascript:void(0);"><i class="fa-brands fa-linkedin-in"></i></a>
                                            <a class="ins" href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- END: Product QuickView -->


    </body>
@endsection
