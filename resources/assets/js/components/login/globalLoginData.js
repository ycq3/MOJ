export default {
    islogin: false,//是否登陆
    userID: '',
    userData: '',
    setData(newData) {//修改函数
        this.islogin = newData;
    },
    setUserID(userID) {
        this.userID = userID;
    },
    setUserData(data) {
        this.userData = data;
    }
}