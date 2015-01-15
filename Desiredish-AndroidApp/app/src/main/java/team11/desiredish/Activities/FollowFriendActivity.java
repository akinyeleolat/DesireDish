package team11.desiredish.Activities;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.LoaderManager.LoaderCallbacks;
import android.content.Context;
import android.content.CursorLoader;
import android.content.Intent;
import android.content.Loader;
import android.database.Cursor;
import android.graphics.drawable.Drawable;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.provider.ContactsContract;
import android.support.v4.app.Fragment;
import android.text.TextUtils;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.inputmethod.EditorInfo;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.List;

import team11.desiredish.R;
import team11.desiredish.TakePhoto.PhotoIntentActivity;


/**
 * A login screen that offers login via email/password.

 */
public class FollowFriendActivity extends Activity implements LoaderCallbacks<Cursor>{
    public final static String EXTRA_MESSAGE = "team11.desiredish.MESSAGE";
    private static final String DEBUG_TAG = "yanjing-debug";
    /**
     * Keep track of the login task to ensure we can cancel it if requested.
     */
    private FollowFriendTask mAuthTask = null;

    // UI references.
    private AutoCompleteTextView mUsernameView;
    private String UserID;
    private TextView mUserid;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
//        getActionBar().setDisplayHomeAsUpEnabled(true);
        setContentView(R.layout.activity_followfriend);
        Intent intent = getIntent();
        UserID = intent.getStringExtra(PhotoIntentActivity.EXTRA_MESSAGE);
        // Set up the login form.
        mUsernameView = (AutoCompleteTextView) findViewById(R.id.username);
        mUserid=(TextView) findViewById(R.id.userid);
        mUserid.setText("My Desiredish ID: "+UserID);
        populateAutoComplete();
        Button mFollowButton = (Button) findViewById(R.id.account_follow_button);
        mFollowButton.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                attemptFollow();
            }
        });

    }

    private void populateAutoComplete() {
        getLoaderManager().initLoader(0, null, this);
    }


    /**
     * Attempts to sign in or register the account specified by the login form.
     * If there are form errors (invalid email, missing fields, etc.), the
     * errors are presented and no actual login attempt is made.
     */
    public void attemptFollow() {
        if (mAuthTask != null) {
            return;
        }
        // Reset errors.
        mUsernameView.setError(null);
        // Store values at the time of the login attempt.
        String username = mUsernameView.getText().toString();

        boolean cancel = false;
        View focusView = null;

        if (TextUtils.isEmpty(username)) {
            mUsernameView.setError(getString(R.string.error_field_required));
            focusView = mUsernameView;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt login and focus the first
            // form field with an error.
            focusView.requestFocus();
        } else {
            // Show a progress spinner, and kick off a background task to
            // perform the user login attempt.
            ConnectivityManager connMgr = (ConnectivityManager)
                    getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo networkInfo = connMgr.getActiveNetworkInfo();
            if (networkInfo != null && networkInfo.isConnected()) {
                mAuthTask = new FollowFriendTask(username);
                mAuthTask.execute((Void) null);
            } else {
                Toast.makeText(this, "No network connection available.",
                        Toast.LENGTH_LONG).show();
            }

        }
    }

    public void attemptFollow(String fbFriend) {
        if (mAuthTask != null) {
            return;
        }

            ConnectivityManager connMgr = (ConnectivityManager)
                    getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo networkInfo = connMgr.getActiveNetworkInfo();
            if (networkInfo != null && networkInfo.isConnected()) {
                mAuthTask = new FollowFriendTask(fbFriend);
                mAuthTask.execute((Void) null);
            } else {
                Toast.makeText(this, "No network connection available.",
                        Toast.LENGTH_LONG).show();
            }


    }






    @Override
    public Loader<Cursor> onCreateLoader(int i, Bundle bundle) {
        return new CursorLoader(this,
                // Retrieve data rows for the device user's 'profile' contact.
                Uri.withAppendedPath(ContactsContract.Profile.CONTENT_URI,
                        ContactsContract.Contacts.Data.CONTENT_DIRECTORY), ProfileQuery.PROJECTION,

                // Select only email addresses.
                ContactsContract.Contacts.Data.MIMETYPE +
                        " = ?", new String[]{ContactsContract.CommonDataKinds.Email
                                                                     .CONTENT_ITEM_TYPE},

                // Show primary email addresses first. Note that there won't be
                // a primary email address if the user hasn't specified one.
                ContactsContract.Contacts.Data.IS_PRIMARY + " DESC");
    }

    @Override
    public void onLoadFinished(Loader<Cursor> cursorLoader, Cursor cursor) {
        List<String> emails = new ArrayList<String>();
        cursor.moveToFirst();
        while (!cursor.isAfterLast()) {
            emails.add(cursor.getString(ProfileQuery.ADDRESS));
            cursor.moveToNext();
        }

        addEmailsToAutoComplete(emails);
    }

    @Override
    public void onLoaderReset(Loader<Cursor> cursorLoader) {

    }

    private interface ProfileQuery {
        String[] PROJECTION = {
                ContactsContract.CommonDataKinds.Email.ADDRESS,
                ContactsContract.CommonDataKinds.Email.IS_PRIMARY,
        };

        int ADDRESS = 0;
        int IS_PRIMARY = 1;
    }


    private void addEmailsToAutoComplete(List<String> emailAddressCollection) {
        //Create adapter to tell the AutoCompleteTextView what to show in its dropdown list.
        ArrayAdapter<String> adapter =
                new ArrayAdapter<String>(FollowFriendActivity.this,
                        android.R.layout.simple_dropdown_item_1line, emailAddressCollection);

        mUsernameView.setAdapter(adapter);
    }

    /**
     * Represents an asynchronous login/registration task used to authenticate
     * the user.
     */
    public class FollowFriendTask extends AsyncTask<Void, Void, String> {

        private final String mUsername;

        FollowFriendTask(String username) {
            mUsername = username;
        }

        @Override
        protected String doInBackground(Void... params) {
            // TODO: attempt authentication against a network service.

            HttpClient httpClient=new DefaultHttpClient();
            HttpPost httpPost=new HttpPost("http://54.165.111.90/desireddish/followfriend/");

            BasicNameValuePair usernameBasicNameValuePair = new BasicNameValuePair("UserId", UserID);
            BasicNameValuePair passwordBasicNameValuePair = new BasicNameValuePair("FriendName", mUsername);

            List<NameValuePair> nameValuePairList = new ArrayList<NameValuePair>();
            nameValuePairList.add(usernameBasicNameValuePair);
            nameValuePairList.add(passwordBasicNameValuePair);

            try {
                // Simulate network access.
         //       Thread.sleep(2000);

                UrlEncodedFormEntity urlEncodedFormEntity = new UrlEncodedFormEntity(nameValuePairList);
                httpPost.setEntity(urlEncodedFormEntity);
                Log.i(DEBUG_TAG, "FollowAct: follow request " + httpPost.getRequestLine());

                try {
                    // HttpResponse is an interface just like HttpPost.
                    //Therefore we can't initialize them
                    HttpResponse httpResponse = httpClient.execute(httpPost);
                    Log.i(DEBUG_TAG, "FollowAct: follow response " + httpResponse.getStatusLine().toString());
                    // According to the JAVA API, InputStream constructor do nothing.
                    //So we can't initialize InputStream although it is not an interface
                    InputStream inputStream = httpResponse.getEntity().getContent();

                    InputStreamReader inputStreamReader = new InputStreamReader(inputStream);

                    BufferedReader bufferedReader = new BufferedReader(inputStreamReader);

                    StringBuilder stringBuilder = new StringBuilder();

                    String bufferedStrChunk = null;

                      while((bufferedStrChunk = bufferedReader.readLine()) != null){
                        stringBuilder.append(bufferedStrChunk);
                    }
                    stringBuilder.append(bufferedReader.readLine());
              //      Log.i(DEBUG_TAG,"LoginAct:result is " + stringBuilder.toString().split("<")[0]);



                    JSONObject result = new JSONObject(stringBuilder.toString());

                    String suc = result.getString("success");
                    return suc;


                }catch (JSONException e) {
                    e.printStackTrace();
                } catch (ClientProtocolException cpe) {
                    Log.i(DEBUG_TAG,"FollowAct:First Exception caz of HttpResponese :" + cpe);
                    cpe.printStackTrace();
                } catch (IOException ioe) {
                    Log.i(DEBUG_TAG,"FollowAct:Second Exception caz of HttpResponse :" + ioe);
                    ioe.printStackTrace();
                }

            } catch (UnsupportedEncodingException uee) {
                Log.i(DEBUG_TAG,"FollowAct:An Exception given because of UrlEncodedFormEntity argument :" + uee);
                uee.printStackTrace();
            }

            return null;
        }

        @Override
        protected void onPostExecute(final String result) {
            mAuthTask = null;

            Log.i(DEBUG_TAG,"FollowAct:result in execute is " + result);

            if (result.equals("-1")) {
                Toast.makeText(FollowFriendActivity.this, "User name does not exist!", Toast.LENGTH_LONG).show();
                finish();
            }
            else if(result.equals("0")){
                Toast.makeText(FollowFriendActivity.this, "You have already followed this friend!", Toast.LENGTH_LONG).show();
                finish();
            }
            else{
                Log.i(DEBUG_TAG,"FollowAct:Log in success!");
                Toast.makeText(FollowFriendActivity.this, "Success!", Toast.LENGTH_LONG).show();
                finish();
            }
        }

        @Override
        protected void onCancelled() {
            mAuthTask = null;
        }
    }


}



