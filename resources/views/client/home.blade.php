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
                                <a href="index.html"><img src="{{ asset('assets/Client/images/logo2.png') }}"
                                        alt="Ulina"></a>
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
                                        <input type="search" name="s" id="s"
                                            placeholder="Type Words and Hit Enter">
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
                        <li data-index="rs-3046" data-transition="random-premium" data-slotamount="default"
                            data-hideafterloop="0" data-hideslideonmobile="off" data-easein="Power3.easeInOut"
                            data-easeout="Power3.easeInOut" data-masterspeed="1000" data-thumb="" data-rotate="0"
                            data-saveperformance="off" data-title="" data-param1="01" data-param2="" data-param3=""
                            data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9=""
                            data-param10="" data-description="">
                            <img src="{{ asset('assets/Client/images/slider/1.png') }}" alt="Ulina Slider"
                                class="rev-slidebg" data-bgposition="center top" data-bgfit="cover"
                                data-bgrepeat="no-repeat" />

                            <div class="tp-caption tp-resizeme layer01 shapeImage"
                                data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-x="['right','right','right','right']" data-hoffset="['192','-100','0','0']"
                                data-y="['bottom','bottom','bottom','bottom']" data-voffset="['71','71','-300','0']"
                                data-width="['auto','auto','60%','auto']" data-height="auto"
                                data-visibility="['on','on','on','off']" data-basealign="slide"><img
                                    src="{{ asset('assets/Client/images/slider/s1.png') }}" alt="Slider Shape" /></div>
                            <div class="tp-caption tp-resizeme rs-parallaxlevel-2 layer03 personImage"
                                data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-x="['right','right','right','right']"  data-hoffset="['180','20','0','0']"
                                data-y="['bottom','bottom','bottom','bottom']" data-voffset="['0','0','0','0']"
                                data-width="['auto','auto','auto','auto']" data-height="auto"
                                data-visibility="['on','on','on','off']" data-textAlign="['left','left','left','right']"
                                data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/person_1.png') }}"
                                    alt="Slider Shape"   data-ww="['auto','auto','auto','auto']"  data-hh="['300px','240px','200px','160px']" /></div>
                            <div class="tp-caption jost theSubTitle" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['-24','-22','-20','-20']" data-fontsize="['16','15','14','13']"
                                data-fontweight="['500','500','500','500']" data-lineheight="['24','22','20','18']"
                                data-width="['auto','auto','auto','100%']" data-height="none" data-whitespace="nowrap"
                                data-color="['#7b9496','#7b9496','#7b9496','#7b9496']" data-type="text"
                                data-responsive_offset="off"
                                data-frames='[{"delay":1100,"speed":400,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['left','left','left','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','10','10','0']">Giảm Giá Hôm
                                Nay</div>
                            <div class="tp-caption jost textLayer theTitles" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['16','16','14','12']" data-fontsize="['30','26','22','18']"
                                data-fontweight="['400','400','400','400']" data-lineheight="['38','34','28','24']"
                                data-width="['480','420','360','100%']" data-height="none" data-whitespace="normal"
                                data-color="['#52586d','#52586d','#52586d','#52586d']" data-type="text"
                                data-responsive_offset="off"
                                data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['left','left','left','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','0','0','0']">Áo Nam & Nữ Thời Thượng</div>
                            <div class="tp-caption ws_nowrap textLayer theBTNS" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['96','88','76','64']" data-fontsize="['14','14','13','12']"
                                data-fontweight="500" data-lineheight="['42','40','38','36']"
                                data-width="['auto','auto','auto','100%']" data-height="auto" data-whitesapce="normal"
                                data-color="#FFFFFF" data-type="text" data-responsive_offset="off"
                                data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['center','center','center','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','0','0','0']"><a
                                    class="ulinaBTN ulinaSliderBTN" href="http://localhost:8000/categories"><span>Xem
                                        Ngay</span></a>
                            </div>
                        </li>
                        <li data-index="rs-3047" data-transition="random-premium" data-slotamount="default"
                            data-hideafterloop="0" data-hideslideonmobile="off" data-easein="Power3.easeInOut"
                            data-easeout="Power3.easeInOut" data-masterspeed="1000" data-thumb="" data-rotate="0"
                            data-saveperformance="off" data-title="" data-param1="01" data-param2="" data-param3=""
                            data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9=""
                            data-param10="" data-description="">
                            <img src="{{ asset('assets/Client/images/slider/1.png') }}" alt="Ulina Slider"
                                class="rev-slidebg" data-bgposition="center top" data-bgfit="cover"
                                data-bgrepeat="no-repeat" />

                            <div class="tp-caption tp-resizeme layer01 shapeImage"
                                data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-x="['right','right','right','right']" data-hoffset="['192','-100','0','0']"
                                data-y="['bottom','bottom','bottom','bottom']" data-voffset="['71','71','-300','0']"
                                data-width="['auto','auto','60%','auto']" data-height="auto"
                                data-visibility="['on','on','on','off']" data-basealign="slide"><img
                                    src="{{ asset('assets/Client/images/slider/s1.png') }}" alt="Slider Shape" /></div>
                            <div class="tp-caption tp-resizeme rs-parallaxlevel-2 layer03 personImage"
                                data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-x="['right','right','right','right']" data-hoffset="['180','20','0','0']"
                                data-y="['bottom','bottom','bottom','bottom']" data-voffset="['0','0','0','0']"
                                data-width="['auto','auto','auto','auto']" data-height="auto"
                                data-visibility="['on','on','on','off']" data-textAlign="['left','left','left','right']"
                                data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/person_2.png') }}"
                                    alt="Slider Shape" data-ww="['auto','auto','auto','auto']"  data-hh="['300px','240px','200px','160px']" /></div>
                            <div class="tp-caption jost theSubTitle" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['-24','-22','-20','-20']" data-fontsize="['16','15','14','13']"
                                data-fontweight="['500','500','500','500']" data-lineheight="['24','22','20','18']"
                                data-width="['auto','auto','auto','100%']" data-height="none" data-whitespace="nowrap"
                                data-color="['#7b9496','#7b9496','#7b9496','#7b9496']" data-type="text"
                                data-responsive_offset="off"
                                data-frames='[{"delay":1100,"speed":400,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['left','left','left','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','10','10','0']">Giảm Giá Hôm
                                Nay</div>
                            <div class="tp-caption jost textLayer theTitles" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['16','16','14','12']" data-fontsize="['30','26','22','18']"
                                data-fontweight="['400','400','400','400']" data-lineheight="['38','34','28','24']"
                                data-width="['480','420','360','100%']" data-height="none" data-whitespace="normal"
                                data-color="['#52586d','#52586d','#52586d','#52586d']" data-type="text"
                                data-responsive_offset="off"
                                data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['left','left','left','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','0','0','0']">Áo Nam & Nữ Thời Thượng</div>
                            <div class="tp-caption ws_nowrap textLayer theBTNS" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['96','88','76','64']" data-fontsize="['14','14','13','12']"
                                data-fontweight="500"data-lineheight="['42','40','38','36']"
                                data-width="['auto','auto','auto','100%']" data-height="auto" data-whitesapce="normal"
                                data-color="#FFFFFF" data-type="text" data-responsive_offset="off"
                                data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['center','center','center','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','0','0','0']"><a
                                    class="ulinaBTN ulinaSliderBTN" href="http://localhost:8000/categories"><span>Xem
                                        Ngay</span></a>
                            </div>
                        </li>
                        <li data-index="rs-3048" data-transition="random-premium" data-slotamount="default"
                            data-hideafterloop="0" data-hideslideonmobile="off" data-easein="Power3.easeInOut"
                            data-easeout="Power3.easeInOut" data-masterspeed="1000" data-thumb="" data-rotate="0"
                            data-saveperformance="off" data-title="" data-param1="01" data-param2="" data-param3=""
                            data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9=""
                            data-param10="" data-description="">
                            <img src="{{ asset('assets/Client/images/slider/1.png') }}" alt="Ulina Slider"
                                class="rev-slidebg" data-bgposition="center top" data-bgfit="cover"
                                data-bgrepeat="no-repeat" />

                            <div class="tp-caption tp-resizeme layer01 shapeImage"
                                data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-x="['right','right','right','right']" data-hoffset="['192','-100','0','0']"
                                data-y="['bottom','bottom','bottom','bottom']" data-voffset="['71','71','-300','0']"
                                data-width="['auto','auto','60%','auto']" data-height="auto"
                                data-visibility="['on','on','on','off']" data-basealign="slide"><img
                                    src="{{ asset('assets/Client/images/slider/s1.png') }}" alt="Slider Shape" /></div>
                            <div class="tp-caption tp-resizeme rs-parallaxlevel-2 layer03 personImage"
                                data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"power3.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-x="['right','right','right','right']" data-hoffset="['180','20','0','0']"
                                 data-y="['bottom','bottom','bottom','bottom']" data-voffset="['0','0','0','0']"
                                data-width="['auto','auto','auto','auto']" data-height="auto"
                               data-visibility="['on','on','on','off']" data-textAlign="['left','left','left','right']"
                                data-basealign="slide"><img src="{{ asset('assets/Client/images/slider/person_3.png') }}"
                                    alt="Slider Shape" data-ww="['auto','auto','auto','auto']" data-hh="['300px','240px','200px','160px']"  /></div>
                            <div class="tp-caption jost theSubTitle" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['-24','-22','-20','-20']" data-fontsize="['16','15','14','13']"
                                data-fontweight="['500','500','500','500']" data-lineheight="['24','22','20','18']"
                                data-width="['36%','42%','50%','60%']" data-height="auto" data-whitespace="nowrap"
                                data-color="['#7b9496','#7b9496','#7b9496','#7b9496']" data-type="text"
                                data-responsive_offset="off"
                                data-frames='[{"delay":1100,"speed":400,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['left','left','left','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','10','10','0']">Giảm Giá Hôm
                                Nay</div>
                            <div class="tp-caption jost textLayer theTitles" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['16','16','14','12']" data-fontsize="['30','26','22','18']"
                                data-fontweight="['400','400','400','400']" data-lineheight="['38','34','28','24']"
                                data-width="['480','420','360','100%']" data-height="none" data-whitespace="normal"
                                data-color="['#52586d','#52586d','#52586d','#52586d']" data-type="text"
                                data-responsive_offset="off"
                                data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['left','left','left','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','0','0','0']">Áo Nam & Nữ Thời Thượng</div>
                            <div class="tp-caption ws_nowrap textLayer theBTNS" data-x="['left','left','left','center']"
                                data-hoffset="['50','40','30','20']" data-y="['middle','middle','middle','middle']"
                                data-voffset="['96','88','76','64']" data-fontsize="['14','14','13','12']"
                                data-fontweight="500" data-lineheight="['42','40','38','36']"
                                data-width="['auto','auto','auto','100%']" data-height="auto" data-whitesapce="normal"
                                data-color="#FFFFFF" data-type="text" data-responsive_offset="off"
                                data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'
                                data-textAlign="['center','center','center','center']" data-paddingtop="['0','0','0','0']"
                                data-paddingright="['0','0','0','0']" data-paddingbottom="['0','0','0','0']"
                                data-paddingleft="['0','0','0','0']" data-marginleft="['0','0','0','0']"><a
                                    class="ulinaBTN ulinaSliderBTN" href="http://localhost:8000/categories"><span>Xem
                                        Ngay</span></a>
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
                            <h3>Giao hàng nhanh</h3>
                            <p>
                                Chúng tôi giao hàng nhanh chóng trên toàn quốc cho mọi đơn hàng.
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
                        <h2 class="secTitle">Sản Phẩm Mới Nhất</h2>
                        <p class="secDesc">Chúng tôi cam kết chất lượng cao, giá cạnh tranh và dịch vụ giao hàng nhanh
                            chóng.</p>
                    </div>
                </div>


                <div id="product-list">
                    @include('client.components.products-list', ['products' => $products])
                </div>



            </div>
            </div>



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

        <!-- BEGIN: Category Section -->
        <section class="categorySection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="secTitle">Danh Mục Của Của Hàng</h2>
                        <p class="secDesc">Tận Hưởng Những Thứ Mới Mẻ Nhất</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="categoryCarousel owl-carousel">
                            @foreach ($categories as $category)
                                <div class="categoryItem01 text-center">
                                    <div class="ci01Thumb">
                                        {{-- nếu có trường thumbnail trong DB --}}
                                        {{-- <img src="{{ asset('storage/' . $category->thumbnail) }}" --}}
                                        <img src="https://aoxuanhe.com/upload/product/axh-149/ao-thun-nam-trang-cao-cap-dep.jpg"
                                            alt="{{ $category->name }}"
                                            style="width:100%; height:auto; object-fit:cover;" />
                                    </div>
                                    <h3>
                                        <a
                                            href="{{ route('client.categories.index', ['category_id' => $category->id]) }}">


                                            {{ $category->name }}
                                        </a>
                                    </h3>
                                    <p>{{ $category->products_count }} Items</p>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
            </section>
            <!-- END: Category Section -->

                <section class="container py-4">
        <h2 class="mb-4"> Sản phẩm bán chạy</h2>
        @include('client.components.products-list', ['products' => $bestSellingProducts])
    </section>

            <!-- BEGIN: Testimonial Section -->
            {{-- <section class="testimonialSection">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-xl-3">
                            <div class="testimoniLeft">
                                <h2 class="secTitle">Những đánh giá sản phẩm nổi bật</h2>
                                <p class="secDesc">Chúng tôi cam kết chất lượng của từng sản phẩm</p>
                                <div class="testimonalNav">
                                    <button class="tprev"><i class="fa-solid fa-angle-left"></i></button>
                                    <button class="tnext"><i class="fa-solid fa-angle-right"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xl-9">
                            <div class="testimonialSliderWrap">
                                <div class="testimonialCarousel owl-carousel">
                                    @foreach($reviews as $review)
                                        <div class="testimonialItem01">
                                            <div class="ti01Header clearfix">
                                                <i class="ulina-quote"></i>
                                                <div class="ti01Rating float-end">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fa-solid fa-star"></i>
                                                        @else
                                                            <i class="fa-regular fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="ti01Author">
                                                <h3>{{ $review->reviewer_name }}</h3>
                                            </div>
                                                    @if($review->product && $review->product->thumbnail)
                                                <div class="ti01Product text-center mt-2">
                                                    <a href="{{ route('product.detail', $review->product->id) }}">
                                                        <img src="{{ asset('storage/' . $review->product->thumbnail) }}"
                                                            alt="{{ $review->product->name }}"
                                                            class="img-fluid"
                                                            style="width: 100%; height: 150px; object-fit: contain;">
                                                    </a>
                                                    <p class="mt-1">{{ $review->product->name }}</p>
                                                </div>
                                            @endif
                                            <div class="ti01Content">
                                                {{ $review->review_text }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> --}}
            <!-- END: Testimonial Section -->

                <section class="coupon">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h2 class="secTitle mb-3">Mã khuyến mãi mới nhất</h2>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($coupons as $coupon)
                                @php
                                    $code = $coupon->code;
                                    $title = $coupon->title;
                                    $discountType = $coupon->discount_type;
                                    $discountValue = $coupon->discount_value;
                                    $minOrderValue = $coupon->restriction?->min_order_value;
                                    $maxDiscountValue = $coupon->restriction?->max_discount_value;
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="coupon-card">
                                        <div class="coupon-left">
                                            <h5 class="discount">
                                                {{ $discountType === 'percent'
                                                    ? 'Giảm ' . rtrim(rtrim(number_format($discountValue, 2, '.', ''), '0'), '.') . '%'
                                                    : 'Giảm ' . number_format($discountValue, 0, ',', '.') . ' VNĐ' }}
                                            </h5>
                                            <p class="condition">
                                                Đơn tối thiểu: {{ number_format($minOrderValue, 0, ',', '.') }} VNĐ
                                            </p>
                                            <p class="expiry">
                                            @if ($coupon->end_date)
                                                    HSD: {{ $coupon->end_date?->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}
                                            @endif
                                            </p>
                                        </div>
                                        <div class="coupon-right">
                                            @auth
                                                <form action="{{ route('client.coupons.claim', $coupon->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-save">Lưu</button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}" class="btn-save">Lưu</a>
                                            @endauth
                                                <a href="{{ route('client.coupons.show', $coupon->id) }}">
                                                    <i class="fas fa-info-circle me-1"></i> Xem chi tiết
                                                </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
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
                        @foreach ($blogs as $blog)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="blogItem02 h-100 shadow-sm border rounded overflow-hidden bg-white">
                                    <a href="{{ route('client.blogs.show', $blog->slug) }}">
                                        <img src="{{ $blog->first_image_from_content ?? asset('images/default-thumbnail.jpg') }}"
                                            alt="{{ $blog->title }}" class="w-100"
                                            style="height: 220px; object-fit: cover; border-radius: .5rem .5rem 0 0;">
                                    </a>
                                    <div class="p-3">
                                        <div class="bi01Meta mb-2 text-muted" style="font-size: 0.9rem;">
                                            <i class="fa-solid fa-clock me-1"></i>
                                            {{ $blog->created_at->format('d/m/Y') }}
                                        </div>
                                        <h3 class="mb-2"
                                            style="font-size: 1rem; line-height: 1.4em; height: 2.8em; overflow: hidden;">
                                            <a href="{{ route('client.blogs.show', $blog->slug) }}"
                                                class="text-dark text-decoration-none">
                                                {{ $blog->title }}
                                            </a>
                                        </h3>
                                        <a href="{{ route('client.blogs.show', $blog->slug) }}"
                                            class="ulinaLink text-primary fw-semibold">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            fetchProducts(url);
        });

        function fetchProducts(url) {
            $.ajax({
                url: url,
                success: function(data) {

                    $('#product-list').html(data);
                    window.history.pushState("", "", url); // Cập nhật URL
                }
            });
        }
    </script>
@endsection
