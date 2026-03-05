 <style>
     html,
     body {
         height: 100%;
         margin: 0;
     }

     #map {
         width: 100%;
         height: 400px;
     }
 </style>

 <br />
 <br />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
 <div class="container">
     <div class="row">
         <h1> UPDATE PETA </h1>
         <div class="col-md-8">
             <div class="card">
                 <div class="card-header">
                     <h5 class="card-title"> Peta </h5>
                 </div>
                 <div class="card-body">

                     <div id='map2' style="width: 100%; height: 800px"></div>
                     <input type="text" value="<?php echo $id ?>" id="id_nonlits" hidden>
                 </div>
             </div>
         </div>
         <div class="col-md-4">
             <div class="card">
                 <div class="card-header">

                     <h5 class="card-title">Detail</h5>
                 </div>
                 <div class="card-body">
                     <?php $this->load->view($tab) ?>

                 </div>
             </div>
         </div>

     </div>
 </div>
 <br />
 <br />
 <br />
 <!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi peta
        var map = L.map('map2').setView([-7.273228079811691, 112.721261602061], 13);

        // Tambahkan layer peta dasar
        let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
        let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 30,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        let basemapControl = {
            "Streets": streets,
            "Satellite": satellite
        };
        L.control.layers(basemapControl).addTo(map);

        // Inisialisasi grup fitur
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Inisialisasi kontrol menggambar
        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems // Pastikan grup fitur yang bisa diedit
            }
        });
        map.addControl(drawControl);

        // Mendapatkan data GeoJSON dari PHP
        var geojsonData = <?php echo $polygon; ?>;

        // Debug data GeoJSON
        console.log('GeoJSON Data:', geojsonData);

        // Cek apakah data GeoJSON valid
        if (geojsonData && geojsonData.geometry && geojsonData.geometry.coordinates) {
            // Membuat layer GeoJSON dan menambahkannya ke peta
            var geojsonLayer = L.geoJSON(geojsonData, {
                onEachFeature: function(feature, layer) {
                    drawnItems.addLayer(layer); // Tambahkan layer ke grup fitur yang bisa diedit
                }
            }).addTo(map);

            // Mendapatkan batas dari layer GeoJSON
            var bounds = geojsonLayer.getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds);
            } else {
                console.error('Invalid bounds:', bounds);
            }
        } else {
            console.error('Invalid GeoJSON data:', geojsonData);
        }

        // Event listeners untuk menggambar
        map.on(L.Draw.Event.CREATED, function(event) {
            var layer = event.layer;
            drawnItems.addLayer(layer);
            // Simpan data jika diperlukan
        });

        map.on(L.Draw.Event.EDITED, function(event) {
            var layers = event.layers;
            layers.eachLayer(function(layer) {
                // Simpan data jika diperlukan

                var geojson = layer.toGeoJSON();
                var id_nonlits = document.getElementById('id_nonlits').value;


                updatedGeoJSON.push(geojson);
            });
            fetch('<?php echo base_url() ?>peta/update_peta', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        geojson: updatedGeoJSON
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        });

        map.on(L.Draw.Event.DELETED, function(event) {
            var layers = event.layers;
            layers.eachLayer(function(layer) {
                // Hapus data jika diperlukan
                console.log('Deleted Layer:', layer);
            });
        });
    });
</script> -->
 <!-- <script>
     //  document.addEventListener('DOMContentLoaded', function() {
     //      // Inisialisasi peta
     //      var map = L.map('map2').setView([-7.273228079811691, 112.721261602061], 13);

     //      // Tambahkan layer peta dasar
     //      let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
     //      let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
     //          maxZoom: 30,
     //          subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
     //      }).addTo(map);

     //      let basemapControl = {
     //          "Streets": streets,
     //          "Satellite": satellite,
     //      };
     //      L.control.layers(basemapControl).addTo(map);

     //      // Mendapatkan data GeoJSON dari PHP
     //      var geojsonData = <?php echo $polygon; ?>;

     //      // Cek apakah data GeoJSON valid
     //      if (geojsonData && geojsonData.geometry && geojsonData.geometry.coordinates) {
     //          var geojsonLayer = L.geoJSON(geojsonData).addTo(map);

     //          var bounds = geojsonLayer.getBounds();
     //          if (bounds.isValid()) {
     //              map.fitBounds(bounds);
     //          } else {
     //              console.error('Invalid bounds:', bounds);
     //          }
     //      } else {
     //          console.error('Invalid GeoJSON data:', geojsonData);
     //      }

     //      // Tambahkan kontrol edit ke peta
     //      var drawControl = new L.Control.Draw({
     //          edit: {
     //              featureGroup: geojsonLayer
     //          }
     //      }).addTo(map);

     //      map.on(L.Draw.Event.EDITED, function(event) {
     //          var layers = event.layers;
     //          var updatedGeoJSON = [];
     //          var id_nonlits = document.getElementById('id_nonlits').value; // Ambil nilai dari input

     //          layers.eachLayer(function(layer) {
     //              var geojson = layer.toGeoJSON();
     //              updatedGeoJSON.push(geojson);
     //          });

     //          // Konfirmasi sebelum mengupdate data
     //          Swal.fire({
     //              title: 'Konfirmasi',
     //              text: 'Apakah Anda yakin ingin memperbarui data ini?',
     //              icon: 'warning',
     //              showCancelButton: true,
     //              confirmButtonText: 'Ya, perbarui',
     //              cancelButtonText: 'Batal'
     //          }).then((result) => {
     //              if (result.isConfirmed) {
     //                  // Kirim data GeoJSON yang diubah ke server
     //                  fetch('<?php echo base_url() ?>peta/save_edited_data', {
     //                          method: 'POST',
     //                          headers: {
     //                              'Content-Type': 'application/json'
     //                          },
     //                          body: JSON.stringify({
     //                              geojson: updatedGeoJSON,
     //                              id: id_nonlits
     //                          })
     //                      })
     //                      .then(response => response.json())
     //                      .then(data => {
     //                          if (data.status === 'success') {
     //                              Swal.fire({
     //                                  title: 'Sukses!',
     //                                  text: 'Data berhasil diperbarui.',
     //                                  icon: 'success',
     //                                  confirmButtonText: 'OK'
     //                              });
     //                          } else {
     //                              Swal.fire({
     //                                  title: 'Error!',
     //                                  text: 'Terjadi kesalahan saat memperbarui data.',
     //                                  icon: 'error',
     //                                  confirmButtonText: 'OK'
     //                              });
     //                          }
     //                      })
     //                      .catch((error) => {
     //                          console.error('Error:', error);
     //                          Swal.fire({
     //                              title: 'Error!',
     //                              text: 'Terjadi kesalahan saat memperbarui data.',
     //                              icon: 'error',
     //                              confirmButtonText: 'OK'
     //                          });
     //                      });
     //              }
     //          });
     //      });
     //  });
//  </script> -->

 <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

 <script>
     document.addEventListener('DOMContentLoaded', function() {
         // Inisialisasi peta
         var map = L.map('map2').setView([-7.273228079811691, 112.721261602061], 13);

         // Tambahkan layer peta dasar
         let streets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
             maxZoom: 20,
             subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
         }).addTo(map);;
         //  let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
         let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
             maxZoom: 20,
             subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
         });


         let basemapControl = {
             "Streets": streets,
             "Satellite": satellite,
         };
         L.control.layers(basemapControl).addTo(map);

         // Mendapatkan data GeoJSON dari PHP
         var geojsonData = <?php echo $polygon; ?>;

         // Tambahkan grup layer untuk pengeditan
         var drawnItems = new L.FeatureGroup();
         map.addLayer(drawnItems);

         // Cek apakah data GeoJSON valid
         if (geojsonData && geojsonData.geometry && geojsonData.geometry.coordinates) {
             var geojsonLayer = L.geoJSON(geojsonData).addTo(drawnItems);

             var bounds = geojsonLayer.getBounds();
             if (bounds.isValid()) {
                 map.fitBounds(bounds);
             } else {
                 console.error('Invalid bounds:', bounds);
             }
         } else {
             // Tampilkan alert jika tidak ada data poligon
             Swal.fire({
                 title: 'Tidak ada poligon!',
                 text: 'Data poligon tidak ditemukan. Anda dapat menggambar poligon baru pada peta.',
                 icon: 'info',
                 confirmButtonText: 'OK'
             });

             // Aktifkan mode menggambar poligon baru
             var drawControl = new L.Control.Draw({
                 draw: {
                     polygon: true, // Izinkan menggambar poligon baru
                     polyline: false,
                     rectangle: false,
                     circle: false,
                     circlemarker: false,
                     marker: false
                 },
                 edit: {
                     featureGroup: drawnItems, // Grup layer untuk pengeditan
                     edit: true
                 }
             }).addTo(map);

             // Event ketika poligon baru dibuat
             map.on(L.Draw.Event.CREATED, function(event) {
                 var layer = event.layer;
                 drawnItems.addLayer(layer);
                 var id_nonlits = document.getElementById('id_nonlits').value; // Ambil nilai dari input

                 var drawnGeoJSON = layer.toGeoJSON();
                 console.log('Poligon baru:', JSON.stringify(drawnGeoJSON));

                 // Kirim data ke server jika diperlukan
                 fetch('<?php echo base_url() ?>peta/save_new_data', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json'
                         },
                         body: JSON.stringify({
                             geojson: drawnGeoJSON,
                             id: id_nonlits
                         })
                     })
                     .then(response => response.json())
                     .then(data => {
                         if (data.status === 'success') {
                             Swal.fire({
                                 title: 'Sukses!',
                                 text: 'Poligon baru berhasil disimpan.',
                                 icon: 'success',
                                 confirmButtonText: 'OK'
                             });
                         } else {
                             Swal.fire({
                                 title: 'Error!',
                                 text: 'Terjadi kesalahan saat menyimpan poligon baru.',
                                 icon: 'error',
                                 confirmButtonText: 'OK'
                             });
                         }
                     })
                     .catch(error => {
                         console.error('Error:', error);
                         Swal.fire({
                             title: 'Error!',
                             text: 'Terjadi kesalahan saat menyimpan poligon baru.',
                             icon: 'error',
                             confirmButtonText: 'OK'
                         });
                     });
             });
         }

         //  Tambahkan kontrol edit untuk poligon yang ada
         var drawControl = new L.Control.Draw({
             edit: {
                 featureGroup: geojsonLayer
             }
         }).addTo(map);

         map.on(L.Draw.Event.EDITED, function(event) {
             var layers = event.layers;
             var updatedGeoJSON = [];
             var id_nonlits = document.getElementById('id_nonlits').value; // Ambil nilai dari input

             layers.eachLayer(function(layer) {
                 var geojson = layer.toGeoJSON();
                 updatedGeoJSON.push(geojson);
             });

             // Konfirmasi sebelum mengupdate data
             Swal.fire({
                 title: 'Konfirmasi',
                 text: 'Apakah Anda yakin ingin memperbarui data ini?',
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonText: 'Ya, perbarui',
                 cancelButtonText: 'Batal'
             }).then((result) => {
                 if (result.isConfirmed) {
                     // Kirim data GeoJSON yang diubah ke server
                     fetch('<?php echo base_url() ?>peta/save_edited_data', {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json'
                             },
                             body: JSON.stringify({
                                 geojson: updatedGeoJSON,
                                 id: id_nonlits
                             })
                         })
                         .then(response => response.json())
                         .then(data => {
                             if (data.status === 'success') {
                                 Swal.fire({
                                     title: 'Sukses!',
                                     text: 'Data berhasil diperbarui.',
                                     icon: 'success',
                                     confirmButtonText: 'OK'
                                 });
                             } else {
                                 Swal.fire({
                                     title: 'Error!',
                                     text: 'Terjadi kesalahan saat memperbarui data.',
                                     icon: 'error',
                                     confirmButtonText: 'OK'
                                 });
                             }
                         })
                         .catch((error) => {
                             console.error('Error:', error);
                             Swal.fire({
                                 title: 'Error!',
                                 text: 'Terjadi kesalahan saat memperbarui data.',
                                 icon: 'error',
                                 confirmButtonText: 'OK'
                             });
                         });
                 }
             });
         });
         map.on(L.Draw.Event.DELETED, function(event) {
             var layers = event.layers;
             var deletedGeoJSON = [];
             var id_nonlits = document.getElementById('id_nonlits').value; // Ambil nilai dari input


             layers.eachLayer(function(layer) {
                 var geojson = layer.toGeoJSON();
                 deletedGeoJSON.push(geojson);
             });

             // Konfirmasi sebelum menghapus data
             Swal.fire({
                 title: 'Konfirmasi',
                 text: 'Apakah Anda yakin ingin menghapus data ini?',
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonText: 'Ya, hapus',
                 cancelButtonText: 'Batal'
             }).then((result) => {
                 if (result.isConfirmed) {
                     // Kirim data GeoJSON yang dihapus ke server
                     fetch('<?php echo base_url() ?>peta/delete_data', {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json'
                             },
                             body: JSON.stringify({
                                 geojson: deletedGeoJSON,
                                 id: id_nonlits
                             })
                         })
                         .then(response => response.json())
                         .then(data => {
                             if (data.status === 'success') {
                                 Swal.fire({
                                     title: 'Sukses!',
                                     text: 'Data berhasil dihapus.',
                                     icon: 'success',
                                     confirmButtonText: 'OK'
                                 });
                             } else {
                                 Swal.fire({
                                     title: 'Error!',
                                     text: 'Terjadi kesalahan saat menghapus data.',
                                     icon: 'error',
                                     confirmButtonText: 'OK'
                                 });
                             }
                         })
                         .catch((error) => {
                             console.error('Error:', error);
                             Swal.fire({
                                 title: 'Error!',
                                 text: 'Terjadi kesalahan saat menghapus data.',
                                 icon: 'error',
                                 confirmButtonText: 'OK'
                             });
                         });
                 }
             });
         });
     });
 </script>