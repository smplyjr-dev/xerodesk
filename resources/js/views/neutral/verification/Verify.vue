<script>
export default {
  layout: "GuestAuth",
  name: "Verify",
  metaInfo: () => ({ title: "Account Verification" }),
  async beforeRouteEnter(to, from, next) {
    let qs = params =>
      Object.keys(params)
        .map(key => `${key}=${params[key]}`)
        .join("&");

    try {
      let { data } = await axios.post(`/email/verify/${to.params.id}?${qs(to.query)}`);
      let params = qs({
        referrer: "verification-page",
        referrer_type: "success",
        referrer_message: data.status
      });

      next(`/?${params}`);
    } catch (error) {
      const errorObj = error.response.data.errors;

      for (const key in errorObj) {
        if (errorObj.hasOwnProperty(key)) {
          const error = errorObj[key];

          let params = qs({
            referrer: "verification-page",
            referrer_type: "danger",
            referrer_message: error[1]
          });

          if (error[0] == "already_verified") next(`/?${params}`);
          if (error[0] == "invalid") next(`/email/resend?${params}`);
        }
      }
    }
  }
};
</script>
