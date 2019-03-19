iPeer integration with Canvas
===================

System setup
-------------------
### Docker network setup
If you are running Docker-Canvas locally, you will need to run the following commands:
```
docker network create canvas_ipeer_network
docker network connect canvas_ipeer_network ipeer_app
docker network connect canvas_ipeer_network docker-canvas_app_1
```
> This assumes that your Docker-Canvas container is called `docker-canvas_app_1` and your iPeer container `ipeer_app`

### Developer key setup
The integration depends on OAuth2 authorization and requires a client ID / secret pair shared between Canvas and iPeer.

#### Generate Client ID / Secret in Canvas
* Login Canvas as system administrator
* Go to **Admin** > **Site Admin** > **Developer Keys**
* Click on the button **+ Developer Key**
* Input a **Key Name**.  Users will see this as the application requesting Canvas authorization when they invoke any of the integration functions (see below) for the first time
* Input the iPeer URI in **Redirect URI (Legacy)** and **Redirect URIs**
* Click **Save**
* A new pair of ID and secret should be generated.  Copy down **ID** and **Key** shown (move the mouse over the entry for the key to be displayed on screen).  Follow the steps below to define them in iPeer

#### Define the Client ID / Secret in iPeer System Parameter
* Login iPeer as administrator
* Go to **Admin** > **System Parameters**
* Update the parameter **system.canvas_client_id** with the **ID** created in Canvas
* Update the parameter **system.canvas_client_secret** with the **Key**.
> **Note**: iPeer may cache the ID / secret system parameters.  If necessary, clear the cache in the folder "app/tmp/cache" and restart the server

### Other System Parameters
Here are some other system parameters related to the Canvas integration:

* **system.canvas_enabled** - Enable / disable functions related to Canvas integration.  If **false**, users won't see these functions (see sections below for available functions)
*  **system.canvas_baseurl** - The base URL of Canvas used by iPeer application logic to invoke Canvas API
* **system.canvas_baseurl_ext** - The base URL of Canvas that users' browser will be forwarded to when performing OAuth authorization
* **system.canvas_force_login** - When users perform the OAuth authorization for the first time, and if they have already logged into Canvas in another browser window, should the system ask for Canvas credential again?  By default, Canvas won't ask for credential again.  Change this setting to **true** to force users to re-enter their Canvas credentials
* **system.canvas_api_timeout** - Timeout value, in seconds, for iPeer calling Canvas API
* **system.canvas_api_default_per_page**, **system.canvas_api_max_retrieve_all**, and **system.canvas_api_max_call** - Canvas API utilizes pagination when retrieving data.  iPeer will automatically issue multiple API calls in order to retrieve all available data.  These parameters control how many records to retrieve on each call, the max total of how many records to retrieve, and the max of how many calls it will make, respectively
* **system.canvas_user_key** - Specify the Canvas field used to map a Canvas user to an iPeer user's login name.  By default, it is the **integration_id**

----------

iPeer Functions Related to Canvas Integration
-------------------
With Canvas integration enabled (system parameter **system.canvas_enabled**), iPeer users can authorize iPeer to synchronize course data between the two systems.  Here are the functions that iPeer users will see.

### Authorization setup
#### First time authorization
When iPeer users click on any Canvas integration functions for the first time, they will be prompted to login to Canvas and authorize iPeer to access Canvas data on their behalf.  The mechanism is based on  OAuth2.  Each user only need to do this once unless they revoked the authorization (for which the user will be prompted to authorize again).

* Users invoke a Canvas integration function (e.g. create an iPeer course based on a Canvas course) from iPeer
* If users are not currently logged into Canvas (or system parameter **system.canvas_force_login** is set to **true**), users will be asked to login Canvas
* If users are authenticated by Canvas successfully, they will be prompted if they want to authorize iPeer to access their Canvas data
* If users authorized the access, they will be redirected back to iPeer to continue.  Otherwise, if users cancelled the authorization, they will be redirected back to iPeer home with proper message displayed

> **Troubleshooting**
>
> * **How to check if users authorized the access?**
> Users can check the authorization status in Canvas.  Login to Canvas.  Go to **Account** > **Settings**. Scroll down to the **Approved Integrations** section.  They will be able to see the details (e.g. last used date/time, expiry date/time etc).  Note that iPeer will auto-renew the token unless users revoked the access.
>
> * **How can users revoke the rights of iPeer accessing Canvas on their behalf?**
> Users can revoke that in Canvas.  Go to **Account** > **Settings**. Scroll down to the **Approved Integrations** section. Click on the **trash can** icon to delete the iPeer token.


### Integrations
These are functions introduced for the integration between iPeer and Canvas.  They are enabled when the system parameter **system.canvas_enabled** is set to **true**.

#### Create iPeer courses based on Canvas courses / linking the courses
Create new iPeer courses and pre-fill the course code, title, instructors, and turors fields based on selected Canvas courses.

* From the menu on top, select **Courses**.  Click on the **Add Course Based on Canvas** button
* Users will be prompted for Canvas authorization if they haven't done so
* Select the Canvas course.  Note that users can only select those courses they are assigned as instructors in Canvas. Click **Next**
* The usual **Add Course** page will be displayed with the following fields pre-filled: **Course**, **Title**, **Instructors**, and **Tutors**.  Review and modify any of the fields and click **Save** to create the iPeer course
* The newly created iPeer course will be linked with the Canvas course.  Users can check that by going to the **Edit Course** page and look for the **Linked with Canvas course** field. Users can also assign / modify the linked course of an existing course from that page.

#### Import student enrollment from Canvas
Import student enrollment into iPeer courses from Canvas courses

* From the menu on top, select **Courses**.  Click on the course to go to the **Course Home** page
* Click on the **Import Students from Canvas** link
* Users will be prompted for Canvas authorization if they haven't done so
* If the iPeer course is already linked with a Canvas course, the **From Canvas Course** field will be pre-filled and disabled.  Otherwise users can select one from the list of Canvas courses that they are assigned as instructors
* If the **Remove old students** option is not checked, Canvas enrollment will be imported and merged with existing iPeer enrollment.  Otherwise, existing iPeer enrollment will be cleared before the import
* Click the **Import** button and  review the import result

#### Compare student enrollment between iPeer and Canvas
If an iPeer course is linked with a Canvas course, users can compare the enrollment between the two.

* From the menu on top, select **Courses**.  Click on the course to go to the **Course Home** page
* Click on the **List Students** link
* If the course is linked with a Canvas course, users will see a description *This course is linked with a Canvas course.  You may compare the enrollment* and a **Compare** button
* Click the **Compare** button and review the enrollment status
* **Students currently enrolled in this iPeer course**: the enrollment status of this iPeer course.  The **Enrolled In Canvas** column will show whether the students also enrolled in Canvas
* **Students enrolled in the Canvas course but not the iPeer course**: Students that are enrolled in the linked Canvas course but not the iPeer course.  Matching iPeer accounts **can** be found.  Users can select them and click the **Enroll selected** button to enroll the students
* **Students enrolled in the Canvas course and without iPeer accounts**:  Students that are enrolled in the linked Canvas course but not the iPeer course.  Matching iPeer accounts **cannot** be found.  Users can use the Import function mentioned above to create the iPeer accounts and enroll them

#### Synchronize groups between iPeer and Canvas
Synchronize the groups and membership between iPeer and Canvas

* From the menu on top, select **Courses**.  Click on the course to go to the **Course Home** page
* Click on the **Sync Canvas Groups** link
* If the iPeer course is already linked with a Canvas course, the  **Canvas Course** field will be pre-filled and disabled.  Otherwise users can select one from the list of Canvas courses that they are assigned as instructors
* Select a Canvas group set.  Groups created in Canvas need to be assigned to a specific Group Set.  Select an existing one from the dropdown or select **(Create new groupset)** to create a new one
* Click **Next** to continue
* If there is no conflict between iPeer groups and Canvas groups (e.g. a students assigned to more than one groups in iPeer.  This is not allowed in Canvas groups), users can select between **Simplified** and **Advanced** modes for the synchronization.  Otherwise only **Advanced** mode is available
* In either mode, users can expand the groups and review the membership.
* In **Simplified** mode, users can click on the **Sync All Groups** button to synchronize.  Groups that only found in iPeer will be created in Canvas (and vice versa).  Memberships of the groups will also be synchronized between iPeer and Canvas
* If there is a conflict or users want to control which group to synchronize, select the **Advanced** mode.  Here, users can review and select specific groups to synchronize from one system to another.  Users can also check the option **Replace group, rather than merge** when synchronizing

> **Prerequisites for a proper group sync**
>
> Please note that group sync will not create new users in Canvas. Any users must already be in that Canvas course and linked properly (using the **system.canvas_user_key**) to be synchronized.
>
> Please also note that Canvas (unlike iPeer) does not allow the same student to exist in more than one group in a single "group set" for a course.
>
> **What are group sets?**
> Canvas uses "Group Sets" to group groups. For example, you can have different group sets for different projects in a course. iPeer does not currently support this concept, but it is a requirement for Canvas.
