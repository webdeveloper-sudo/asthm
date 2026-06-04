/**
 * ACCHM Online Admissions - Google Sheets Integration Script
 * 
 * Instructions:
 * 1. Open Google Sheets and create a new spreadsheet.
 * 2. Click Extensions > Apps Script.
 * 3. Delete any code in the editor and paste this script.
 * 4. Replace the header row or spreadsheet names if needed.
 * 5. Click "Deploy" (top right) > "New deployment".
 * 6. Under "Select type", select "Web app".
 * 7. Set:
 *    - Description: Admissions form integration
 *    - Execute as: Me (your-email@gmail.com)
 *    - Who has access: Anyone (Important: Do not select "Anyone with Google account")
 * 8. Click "Deploy". Authorize permissions if prompted.
 * 9. Copy the generated Web App URL (ends in /exec).
 * 10. Paste this URL into the `GOOGLE_SCRIPT_URL` constant inside `admissions_sheet.php`.
 */

function doPost(e) {
  // Add CORS headers to responses
  var headers = {
    "Access-Control-Allow-Origin": "*",
    "Access-Control-Allow-Methods": "POST, GET, OPTIONS",
    "Access-Control-Allow-Headers": "Content-Type"
  };

  try {
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    
    // Auto-create header row if sheet is completely empty
    if (sheet.getLastRow() === 0) {
      sheet.appendRow([
        "Timestamp", 
        "Student Name", 
        "Parent Name", 
        "Mobile No", 
        "Email", 
        "State", 
        "District", 
        "Board", 
        "Total Mark", 
        "Course Interest", 
        "Callback Required", 
        "Callback Time From", 
        "Callback Time Till", 
        "Campus Visit Date", 
        "Message"
      ]);
      // Format headers: bold and grey background
      sheet.getRange(1, 1, 1, 15)
           .setFontWeight("bold")
           .setBackground("#f3f4f6");
    }
    
    // Parse incoming parameters
    var params = e.parameter;
    
    // Extract fields
    var timestamp       = new Date();
    var student_name    = params.student_name || "";
    var parent_name     = params.parent_name || "";
    var mobile          = params.mobile || "";
    var email           = params.email || "";
    var state           = params.state || "";
    var district        = params.district || "";
    var board           = params.board || "";
    var total_mark      = params.total_mark || "";
    var course_interest = params.course_interest || "";
    var callback        = params.callback || "";
    
    // Handle callback times
    var callback_from = "";
    var callback_till = "";
    if (callback === "Yes") {
      var cb_from_hh   = params.cb_from_hh   || "12";
      var cb_from_mm   = params.cb_from_mm   || "00";
      var cb_from_ampm = params.cb_from_ampm || "AM";
      var cb_till_hh   = params.cb_till_hh   || "12";
      var cb_till_mm   = params.cb_till_mm   || "00";
      var cb_till_ampm = params.cb_till_ampm || "AM";
      var cb_date      = params.cb_date      || "";
      
      callback_from = (cb_date ? cb_date + " " : "") + cb_from_hh + ":" + cb_from_mm + " " + cb_from_ampm;
      callback_till = cb_till_hh + ":" + cb_till_mm + " " + cb_till_ampm;
    }
    
    var campus_date = params.campus_date || "";
    var message     = params.message     || "";
    
    // Format timestamp nicely
    var formattedTime = Utilities.formatDate(timestamp, Session.getScriptTimeZone(), "yyyy-MM-dd HH:mm:ss");
    
    // Append row
    sheet.appendRow([
      formattedTime,
      student_name,
      parent_name,
      mobile,
      email,
      state,
      district,
      board,
      total_mark,
      course_interest,
      callback,
      callback_from,
      callback_till,
      campus_date,
      message
    ]);
    
    // Return a JSON response
    var output = JSON.stringify({
      status: "success",
      message: "Application submitted successfully to Google Sheets! Our admissions team will contact you shortly."
    });
    
    return ContentService.createTextOutput(output)
      .setMimeType(ContentService.MimeType.JSON)
      .setHeaders(headers);
      
  } catch (error) {
    // Return error message
    var output = JSON.stringify({
      status: "error",
      message: "Google Sheets Error: " + error.toString()
    });
    
    return ContentService.createTextOutput(output)
      .setMimeType(ContentService.MimeType.JSON)
      .setHeaders(headers);
  }
}

// Handle OPTIONS preflight requests if needed
function doOptions(e) {
  var headers = {
    "Access-Control-Allow-Origin": "*",
    "Access-Control-Allow-Methods": "POST, GET, OPTIONS",
    "Access-Control-Allow-Headers": "Content-Type",
    "Access-Control-Max-Age": "86400"
  };
  return ContentService.createTextOutput("")
    .setMimeType(ContentService.MimeType.TEXT)
    .setHeaders(headers);
}
