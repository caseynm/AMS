# Instructions for Azure App Registration (for Microsoft Graph API - OneDrive Integration)

To allow this application (Accreditation Management System - AMS) to access OneDrive files via the Microsoft Graph API, you need to register the application in your Azure Active Directory (Azure AD) tenant. Please follow these steps:

**1. Access Azure Portal:**
   - Go to the [Azure portal](https://portal.azure.com).
   - Sign in with an account that has permissions to manage applications in your Azure AD tenant (this is typically an administrator account).

**2. Navigate to App Registrations:**
   - In the Azure portal's top search bar, type "**Azure Active Directory**" and select it from the results.
   - In the Azure Active Directory overview page, under the "Manage" section in the left navigation pane, click on "**App registrations**".
   - On the "App registrations" page, click on the "**+ New registration**" button.

**3. Register the Application:**
   - **Name:** Enter a descriptive name for your application. For example: `AMS OneDrive Integration` or `Accreditation Management System GraphAPI`.
   - **Supported account types:**
     - Select "**Accounts in this organizational directory only (Default Directory only - Single tenant)**". This is the most common and recommended setting for an internal application used by your organization.
     - *(If you have specific needs for users outside your organization or personal Microsoft accounts to access this, consult your IT administrator. For initial setup, single-tenant is preferred.)*
   - **Redirect URI (IMPORTANT):** This is where Azure AD will send the authentication response after a user signs in.
     - From the "Select a platform" dropdown, choose "**Web**".
     - In the URI field, enter the following value exactly:
       `http://localhost/AMS/index.php?url=onedrive/callback`
       *(Note: If your local development setup for AMS uses a different port or domain, please adjust `http://localhost/AMS/` accordingly. The `/index.php?url=onedrive/callback` part should remain unless the application's routing for the callback changes.)*
     - **Crucial:** The Redirect URI entered here must exactly match the one the application will use. We will confirm this during integration. If you deploy the application to a production server later, you will need to return to this Azure App Registration and add the production Redirect URI to the list of allowed URIs.
   - Click the "**Register**" button at the bottom of the page.

**4. Obtain Application (Client) ID and Directory (Tenant) ID:**
   - After the application is registered, you will be taken to its "Overview" page.
   - Locate and copy the **Application (Client) ID**. This is a unique identifier for your app.
   - Locate and copy the **Directory (Tenant) ID**. This identifies your Azure AD tenant.
   - **Needed for application configuration:** `Application (Client) ID`, `Directory (Tenant) ID`.

**5. Create a Client Secret:**
   - In the left navigation pane for your newly registered application, under "Manage", click on "**Certificates & secrets**".
   - Select the "**Client secrets**" tab.
   - Click on "**+ New client secret**".
   - **Description:** Enter a meaningful description for the secret, for example, `AMS OneDrive Integration Secret - Dev`.
   - **Expires:** Choose an appropriate expiration period for the secret (e.g., 6 months, 12 months). Keep in mind that you will need to generate a new secret before the old one expires.
   - Click "**Add**".
   - **VERY IMPORTANT:** Once the client secret is created, its **Value** will be displayed on the screen. **Copy this value immediately and store it in a secure location.** You will **not** be able to see this secret value again after you leave this page.
   - **Needed for application configuration:** `Client Secret Value`.

**6. Configure API Permissions (Microsoft Graph):**
   - In the left navigation pane for your application, under "Manage", click on "**API permissions**".
   - Click on "**+ Add a permission**".
   - In the "Request API permissions" pane that appears, select "**Microsoft Graph**" (it's usually under "Commonly used Microsoft APIs").
   - Choose "**Delegated permissions**". This type of permission allows the application to access the API as the signed-in user.
   - You will now see a list of permissions. You need to add the following:
     - Search for and select **`Files.ReadWrite`**. (Description: Allows the app to read, create, update, and delete all files the signed-in user can access). This permission is necessary for the application to manage documents in the user's OneDrive.
       *Alternatively, for more restricted access, `Files.ReadWrite.AppFolder` could be used if the application is designed to use a specific application folder in OneDrive. For general document management as described, `Files.ReadWrite` is often a starting point.*
     - Search for and select **`offline_access`**. (Description: Allows the app to access data on behalf of the user for an extended period, even when the user is not actively using the application. This is required to obtain refresh tokens).
     - Search for and select **`User.Read`**. (Description: Allows users to sign-in to the app, and allows the app to read the profile of signed-in users). This is essential for the authentication process.
   - After selecting the permissions, click the "**Add permissions**" button at the bottom of the pane.
   - **Grant Admin Consent (If Necessary):**
     - Back on the "API permissions" page, some permissions might require admin consent.
     - If you see a button labeled "**Grant admin consent for [Your Tenant Name]**" and it is enabled, an Azure AD administrator for your tenant must click this button to grant the permissions for all users in the organization.
     - If the status already shows "Granted for [Your Tenant Name]" for the added permissions, or if the button is disabled, the consent might have been granted automatically or is not required for these specific permissions in your tenant configuration.

**Summary of Information to Provide for Application Configuration:**

Please securely gather and provide the following details so they can be configured into the AMS application to enable OneDrive integration:

1.  **Application (Client) ID:** (Copied from the app's "Overview" page)
2.  **Directory (Tenant) ID:** (Copied from the app's "Overview" page)
3.  **Client Secret Value:** (The secret string **Value** you copied immediately after creating it)
4.  The exact **Redirect URI** you registered in Azure AD (e.g., `http://localhost/AMS/index.php?url=onedrive/callback`)

**Security Note:** Treat the Client Secret Value like a password. It should be stored securely and not exposed in client-side code or insecurely in configuration files that are publicly accessible. The application will store this on the server-side.

These steps will enable the AMS application to request the necessary permissions to interact with Microsoft OneDrive on behalf of your users.
