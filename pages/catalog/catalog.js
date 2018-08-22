// pages/catalog/catalog.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
      service_type:"",
      technician:[
        {id:0, name: "小明", image: "https://img2.woyaogexing.com/2018/08/12/18256e62c0db0dae!400x400_big.jpg", available: true},
        { id:1,name: "小李", image: "https://img2.woyaogexing.com/2018/08/12/18256e62c0db0dae!400x400_big.jpg",  available: true},
        { id: 2 ,name: "小红", image: "",  available: false },
        { id: 3,name: "小华", image: "",available: true },
        { id: 4,name: "小张", image: "",available: false },
        { id: 5,name: "小丽", image: "", available: true },
        { id: 6,name: "小亮", image: "", available: true },
      ],
      showed_tech:[
        { id: 0, name: "小明", image: "https://img2.woyaogexing.com/2018/08/12/18256e62c0db0dae!400x400_big.jpg", available: true },
        { id: 1, name: "小李", image: "https://img2.woyaogexing.com/2018/08/12/18256e62c0db0dae!400x400_big.jpg", available: true },
        { id: 2, name: "小红", image: "", available: false },
        { id: 3, name: "小华", image: "", available: true },
        { id: 4, name: "小张", image: "", available: false },
        { id: 5, name: "小丽", image: "", available: true },
        { id: 6, name: "小亮", image: "", available: true },
      ],
      price:120,
      discount:1,
      duration:90,
      inputVal:"",
      inputShowed:false,
      filter_index:0,
      filter_list:["显示全部","仅显示可预约"],
  },
  set_filter:function(e){
    this.setData({filter_index:e.detail.value})
    this.setData({ showed_tech: [] })
    if (this.data.inputVal.length == 0) {
      for (var i = 0; i < this.data.technician.length; i++) {
        var item = this.data.technician[i]
        this.apply_filter(item)
      }
    } else {
      this.setData({ showed_tech: [] })
      for (var i = 0; i < this.data.technician.length; i++) {
        var item = this.data.technician[i]
        if (item.name == this.data.inputVal) {
          this.apply_filter(item)
        }
      }
    }
    this.setData({ showed_tech: this.data.showed_tech })
  },
  jump_detail:function(event){
    wx.navigateTo({
      url: '../technician-detail/technician-detail?id=' + event.currentTarget.id,
      success: function(res) {},
      fail: function(res) {},
      complete: function(res) {},
    })
  },
  inputTyping:function(e){
    this.setData({ inputVal: e.detail.value })
    this.setData({ showed_tech: [] })
    if (this.data.inputVal.length == 0) {
      for (var i = 0; i < this.data.technician.length; i++) {
        var item = this.data.technician[i]
        this.apply_filter(item)
      }
    }else{
      this.setData({ showed_tech: [] })
      for (var i = 0; i < this.data.technician.length; i++) {
        var item = this.data.technician[i]
        if (item.name == this.data.inputVal) {
          this.apply_filter(item)
        }
      }
    }
    this.setData({ showed_tech: this.data.showed_tech })
  },
  apply_filter:function(item){
    var temp_val = ""
    if (this.data.inputVal.length == 0)temp_val=item.name;
    else temp_val = this.data.inputVal;

    if (item.name == temp_val) {
      if (this.data.filter_index == 0) {
        this.data.showed_tech.push({ id: item.id, name: item.name, image: item.image, available: item.available })
      } else if (this.data.filter_index == 1) {
        if(item.available == true){
          this.data.showed_tech.push({ id: item.id, name: item.name, image: item.image, available: item.available })
        }
      }
    }
  },
  clearInput:function(){
    this.setData({inputValue:""})
  },
  showInput:function(){
    this.setData({ inputShowed: true})
  },
  hideInput:function(){
    this.setData({ inputShowed: false })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
      this.setData({service_type:options.type})
      /*这里留读取技师的函数






      */
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})