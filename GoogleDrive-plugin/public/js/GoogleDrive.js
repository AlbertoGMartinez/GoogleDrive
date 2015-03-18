
jQuery(function($) {
    $("#cv").click(
        function() {
            return googleDriveMain();
        }
    );
});
    
function googleDriveMain()
{
  // The developer key obtained from the Google Developers Console. Replace with your own (wp-admin).
  var developerKey = document.getElementById("googleDrivejs").getAttribute("data-google_drive_api_key");

  // The Client ID obtained from the Google Developers Console. Replace with your own Client ID (wp-admin).
  var clientId = document.getElementById("googleDrivejs").getAttribute("data-developer_key");

  // Replace with your own App ID. (Its the first number in your Client ID)(wp-admin)
  var appId =document.getElementById("googleDrivejs").getAttribute("data-app_id");

  // Scope to use to access user's Drive items.
  var scope = ['https://www.googleapis.com/auth/drive'];

  var pickerApiLoaded = false;

  var oauthToken;

  // Use the API Loader script to load google.picker and gapi.auth.
  function onApiLoad() {
    gapi.load('auth', {'callback': onAuthApiLoad});
    gapi.load('picker', {'callback': onPickerApiLoad});
  }

  function onAuthApiLoad() {
    window.gapi.auth.authorize(
      {
       'client_id': clientId,
       'scope': scope,
       'immediate': false
      },
    handleAuthResult);
  }

  function onPickerApiLoad() {
    pickerApiLoaded = true;
    createPicker();
  }

  function handleAuthResult(authResult) {
    if (authResult && !authResult.error) {
      oauthToken = authResult.access_token;
      createPicker();
    }
  }

  // Create and render a Picker object.
  function createPicker() {
    if (pickerApiLoaded && oauthToken) {
      var view = new google.picker.View(google.picker.ViewId.DOCS);
      view.setMimeTypes("application/pdf");
      var picker = new google.picker.PickerBuilder()
      .enableFeature(google.picker.Feature.NAV_HIDDEN)
      .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
      .setAppId(appId)
      .setOAuthToken(oauthToken)
      .addView(view)
      .addView(new google.picker.DocsUploadView())
      .setDeveloperKey(developerKey)
      .setCallback(pickerCallback)
      .build();
      picker.setVisible(true);
    }
  }

  function postDownload (resp) {
    var link;
    var data = jQuery.parseJSON( resp );
    if (data.mimeType == "application/pdf"){
      link = data.downloadUrl;
      initPicker (link);
    } else {
        link = (data.exportLinks)['application/pdf'];
      }
  }

  // Callback implementation to retieve the information from the files from googleDrive
  function pickerCallback(data) {
    if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
      var doc = data[google.picker.Response.DOCUMENTS][0];
      var document_id = doc[google.picker.Document.ID];
      var accessToken = gapi.auth.getToken().access_token;
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'https://www.googleapis.com/drive/v2/files/' + document_id);
      xhr.setRequestHeader('Authorization', 'Bearer ' + accessToken);
      xhr.onload = function() {
        postDownload(xhr.responseText);
      };
      xhr.onerror = function() {
        checkAuth();
      };
      xhr.send();
    }  
  }

  onApiLoad();

  function initPicker(url) {
    x = 0;
    f = 'cv_googleDrive';
    var mi = document.createElement("input");
    mi.setAttribute('type', 'hidden');
    mi.setAttribute('class', 'file');
    mi.setAttribute('name', 'upload[file_googleDrive][googleDrive][' + x + '][url]');
    mi.setAttribute('value', '');
    mi.setAttribute("value",  url) ;
    document.getElementById('cv').appendChild(mi);
  }      
}
